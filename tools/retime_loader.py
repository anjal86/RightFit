#!/usr/bin/env python3
"""Losslessly retime the existing animated GIF and WebP.

Only metadata is changed: GIF Graphics Control Extension delays and loop
extension, and WebP ANMF durations and ANIM loop count. No frames or pixels are
decoded or re-encoded. Total playback time: 5,600 milliseconds. Both formats
stop after exactly one playback rather than looping.
"""
from __future__ import annotations

from pathlib import Path

TARGET_MS = 5600
ASSETS = Path(__file__).resolve().parents[1] / "assets"


def distribute(durations: list[int], target: int, minimum: int = 1) -> list[int]:
    if not durations:
        raise ValueError("No animation frames found")
    if target < len(durations) * minimum:
        raise ValueError("Not enough time for requested minimum delay")
    baseline = [max(minimum, d) for d in durations]
    total = sum(baseline)
    scaled = [max(minimum, (d * target + total // 2) // total) for d in baseline]
    remainder = target - sum(scaled)
    direction = 1 if remainder >= 0 else -1
    index = 0
    while remainder:
        j = index % len(scaled)
        if direction == 1 or scaled[j] > minimum:
            scaled[j] += direction
            remainder -= direction
        index += 1
        if index > len(scaled) * max(target, 1) * 2:
            raise ValueError("Could not distribute frame durations")
    return scaled


def gif_parts(data: bytes) -> tuple[list[int], list[tuple[int, int]]]:
    """Return delay field byte offsets and loop application-extension ranges."""
    if data[:6] not in (b"GIF87a", b"GIF89a"):
        raise ValueError("Input is not a GIF")
    if len(data) < 14:
        raise ValueError("Truncated GIF")
    pos = 13
    flags = data[10]
    if flags & 0x80:
        pos += 3 * (1 << ((flags & 7) + 1))
    delays: list[int] = []
    loops: list[tuple[int, int]] = []

    def skip_blocks(p: int) -> int:
        while True:
            size = data[p]
            p += 1
            if size == 0:
                return p
            p += size

    while pos < len(data):
        marker = data[pos]
        if marker == 0x3B:
            break
        if marker == 0x2C:
            if pos + 10 > len(data):
                raise ValueError("Truncated GIF image descriptor")
            local_flags = data[pos + 9]
            pos += 10
            if local_flags & 0x80:
                pos += 3 * (1 << ((local_flags & 7) + 1))
            pos += 1  # LZW code-size byte
            pos = skip_blocks(pos)
        elif marker == 0x21:
            label = data[pos + 1]
            if label == 0xF9:
                if data[pos + 2] != 4 or data[pos + 7] != 0:
                    raise ValueError("Unexpected GIF Graphic Control Extension")
                delays.append(pos + 4)
                pos += 8
            else:
                start = pos
                first_size = data[pos + 2]
                identifier = data[pos + 3:pos + 3 + first_size]
                pos = skip_blocks(pos + 2)
                if label == 0xFF and identifier in (b"NETSCAPE2.0", b"ANIMEXTS1.0"):
                    loops.append((start, pos))
        else:
            raise ValueError(f"Unrecognised GIF block at {pos}: {marker:02x}")
    if not delays:
        raise ValueError("No Graphic Control Extensions found")
    return delays, loops


def retime_gif(path: Path) -> None:
    data = path.read_bytes()
    delays, loops = gif_parts(data)
    old = [max(1, int.from_bytes(data[p:p + 2], "little")) for p in delays]
    # GIF delay resolution is 1 centisecond, so 560 cs = 5.6 seconds.
    new = distribute(old, TARGET_MS // 10, minimum=2)
    output = bytearray(data)
    for p, ms10 in zip(delays, new):
        output[p:p + 2] = ms10.to_bytes(2, "little")
    for start, end in reversed(loops):
        del output[start:end]
    new_delays, new_loops = gif_parts(bytes(output))
    observed = sum(int.from_bytes(output[p:p + 2], "little") for p in new_delays)
    if observed != TARGET_MS // 10 or new_loops or len(new_delays) != len(delays):
        raise ValueError("GIF verification failed")
    path.write_bytes(output)
    print(f"GIF: {len(delays)} frames, {sum(old)*10}ms -> {TARGET_MS}ms, removed {len(loops)} looping extensions")


def webp_parts(data: bytes) -> tuple[list[int], int]:
    if data[:4] != b"RIFF" or data[8:12] != b"WEBP":
        raise ValueError("Input is not a RIFF WebP")
    pos = 12
    durations: list[int] = []
    anim_loop: int | None = None
    while pos + 8 <= len(data):
        kind = data[pos:pos + 4]
        size = int.from_bytes(data[pos + 4:pos + 8], "little")
        start = pos + 8
        if kind == b"ANIM":
            if size < 6:
                raise ValueError("Malformed WebP ANIM")
            anim_loop = start + 4
        elif kind == b"ANMF":
            if size < 16:
                raise ValueError("Malformed WebP ANMF")
            durations.append(start + 12)
        pos = start + size + (size & 1)
    if anim_loop is None or not durations:
        raise ValueError("Not an animated WebP")
    return durations, anim_loop


def retime_webp(path: Path) -> None:
    data = path.read_bytes()
    offsets, loop_pos = webp_parts(data)
    old = [max(1, int.from_bytes(data[p:p + 3], "little")) for p in offsets]
    new = distribute(old, TARGET_MS, minimum=20)
    output = bytearray(data)
    for p, duration in zip(offsets, new):
        if duration > 0xFFFFFF:
            raise ValueError("Frame duration exceeds WebP limit")
        output[p:p + 3] = duration.to_bytes(3, "little")
    # Animated WebP: loop count 0 = forever, 1 = exactly one play.
    output[loop_pos:loop_pos + 2] = (1).to_bytes(2, "little")
    offsets2, loop2 = webp_parts(bytes(output))
    observed = sum(int.from_bytes(output[p:p + 3], "little") for p in offsets2)
    if observed != TARGET_MS or len(offsets2) != len(offsets) or int.from_bytes(output[loop2:loop2 + 2], "little") != 1:
        raise ValueError("WebP verification failed")
    path.write_bytes(output)
    print(f"WebP: {len(offsets)} frames, {sum(old)}ms -> {TARGET_MS}ms, loop count 1")


if __name__ == "__main__":
    retime_gif(ASSETS / "loader.gif")
    retime_webp(ASSETS / "loader.webp")
