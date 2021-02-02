<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redemption Setup</title>
</head>
<body>
    <p>Put this into your OBS setup:</p>
    @if($listener->status() == 409 || $listener->status() == 200)
        https://redemptions.cardhouse.online?b={{ $broadcaster->id }}
    @else
        There was an issue setting up the listener.
        <span>{{ $listener->json('message') }}</span>
    @endif
</body>
</html>