<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">
    <h1>Chỉnh sửa bài viết</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nội dung</label>
            <textarea name="content" class="form-control" rows="5" required>{{ old('content', $post->content) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh hiện tại</label>
            @if($post->image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $post->image) }}" alt="Ảnh cũ" width="150" class="img-thumbnail">
            </div>
            @else
            <p class="text-muted">Chưa có ảnh</p>
            @endif
            <label class="form-label">Tải lên ảnh mới (nếu muốn thay đổi)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('post.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</body>

</html>