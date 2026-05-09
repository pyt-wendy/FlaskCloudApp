$items = @{
    "smartphone_x.jpg" = "https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&w=800";
    "laptop_pro.jpg" = "https://images.pexels.com/photos/18105/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=800"
}

$ua = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"

foreach ($item in $items.GetEnumerator()) {
    Write-Host "Re-downloading $($item.Key)..."
    try {
        Invoke-WebRequest -Uri $item.Value -OutFile "uploads/$($item.Key)" -UserAgent $ua -ErrorAction Stop
        Write-Host "Success!"
    } catch {
        Write-Host "Failed: $($_.Exception.Message)"
    }
}
