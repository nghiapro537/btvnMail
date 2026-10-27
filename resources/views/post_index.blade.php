<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh sách bài viết</h1>
        @auth
        <div>
            <span class="me-3">Xin chào, <strong>{{ auth()->user()->name }}</strong></span>
            <a href="{{ route('post.create') }}" class="btn btn-primary">Tạo bài viết mới</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline ms-2">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Đăng xuất</button>
            </form>
        </div>
        @else
        <div>
            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Đăng nhập</a>
            <a href="{{ route('register') }}" class="btn btn-success">Đăng ký</a>
        </div>
        @endauth
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($posts as $post)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Ảnh bài viết" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text text-truncate">{{ $post->content }}</p>
                    <a href="{{ route('post.show', $post->id) }}" class="btn btn-info btn-sm">Xem chi tiết</a>

                    @auth
                    @if($post->user_id === auth()->id())
                    <a href="{{ route('post.edit', $post->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('post.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
</body>

</html>