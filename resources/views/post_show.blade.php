<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>{{ $post->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">
    <h1>{{ $post->title }}</h1>
    <p class="text-muted">Đăng vào ngày: {{ $post->created_at }}</p>

    @if($post->image)
    <div class="mb-4">
        <img src="{{ asset('storage/' . $post->image) }}" alt="Ảnh bài viết" class="img-fluid rounded" style="max-height: 400px;">
    </div>
    @endif

    <div class="content mb-4">
        <p>{{ $post->content }}</p>
    </div>

    <a href="{{ route('post.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
</body>

</html>