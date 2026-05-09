$items = @{
    "face_mask.jpg" = "https://images.pexels.com/photos/3970330/pexels-photo-3970330.jpeg?auto=compress&cs=tinysrgb&w=800";
    "dettol.jpg" = "https://images.pexels.com/photos/4210611/pexels-photo-4210611.jpeg?auto=compress&cs=tinysrgb&w=800";
    "geisha.jpg" = "https://images.pexels.com/photos/6621151/pexels-photo-6621151.jpeg?auto=compress&cs=tinysrgb&w=800";
    "sunscreen.jpg" = "https://images.pexels.com/photos/5431668/pexels-photo-5431668.jpeg?auto=compress&cs=tinysrgb&w=800";
    "hand_sanitizer.jpg" = "https://images.pexels.com/photos/4099414/pexels-photo-4099414.jpeg?auto=compress&cs=tinysrgb&w=800";
    "energy_drink.jpg" = "https://images.pexels.com/photos/2043644/pexels-photo-2043644.jpeg?auto=compress&cs=tinysrgb&w=800";
    "velvex.jpg" = "https://images.pexels.com/photos/3951628/pexels-photo-3951628.jpeg?auto=compress&cs=tinysrgb&w=800";
    "coleslaw.jpg" = "https://images.pexels.com/photos/1213502/pexels-photo-1213502.jpeg?auto=compress&cs=tinysrgb&w=800"
}

$ua = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"

if (-not (Test-Path "uploads")) { New-Item -ItemType Directory -Path "uploads" }

foreach ($item in $items.GetEnumerator()) {
    Write-Host "Downloading $($item.Key)..."
    try {
        Invoke-WebRequest -Uri $item.Value -OutFile "uploads/$($item.Key)" -UserAgent $ua -ErrorAction Stop
        Write-Host "Success!"
    } catch {
        Write-Host "Failed: $($_.Exception.Message)"
    }
}
