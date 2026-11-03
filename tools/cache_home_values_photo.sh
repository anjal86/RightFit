#!/usr/bin/env bash
set -euo pipefail
url='https://images.pexels.com/photos/7176052/pexels-photo-7176052.jpeg?auto=compress&cs=tinysrgb&w=1100'
curl -fLSs --retry 3 --retry-delay 2 --retry-all-errors --max-time 45 "$url" -o assets/values-consultation.jpg
file assets/values-consultation.jpg
test "$(wc -c < assets/values-consultation.jpg)" -gt 24000
file --mime-type assets/values-consultation.jpg | grep -q 'image/jpeg'
echo "Self-hosted Pexels image downloaded and verified."
