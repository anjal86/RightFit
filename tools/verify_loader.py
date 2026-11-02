#!/usr/bin/env python3
"""Verify the published loader assets, including duration and play-once metadata."""
from pathlib import Path
from retime_loader import gif_parts, webp_parts, TARGET_MS, ASSETS


def verify_gif() -> None:
    source = (ASSETS / "loader.gif").read_bytes()
    delay_positions, loop_ranges = gif_parts(source)
    duration = 10 * sum(
        int.from_bytes(source[pos:pos + 2], "little")
        for pos in delay_positions
    )
    assert duration == TARGET_MS, f"GIF duration is {duration}ms, expected {TARGET_MS}ms"
    assert not loop_ranges, "GIF still contains a repeat/loop application extension"
    assert len(delay_positions) == 125, f"GIF frame count changed: {len(delay_positions)}"
    print(f"PASS GIF: {len(delay_positions)} original frames, {duration}ms, no loop extension")


def verify_webp() -> None:
    source = (ASSETS / "loader.webp").read_bytes()
    frame_positions, loop_position = webp_parts(source)
    duration = sum(
        int.from_bytes(source[pos:pos + 3], "little")
        for pos in frame_positions
    )
    repeat = int.from_bytes(source[loop_position:loop_position + 2], "little")
    assert duration == TARGET_MS, f"WebP duration is {duration}ms, expected {TARGET_MS}ms"
    assert repeat == 1, f"WebP loop count should be 1 play, got {repeat}"
    assert len(frame_positions) == 125, f"WebP frame count changed: {len(frame_positions)}"
    print(f"PASS WebP: {len(frame_positions)} original frames, {duration}ms, one playback")


if __name__ == "__main__":
    verify_gif()
    verify_webp()
