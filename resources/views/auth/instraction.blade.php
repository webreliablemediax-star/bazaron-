@extends('backend.layouts.master')

@section('contents')
    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h4 class="mb-0">
                    {{ isset($edit) ? 'Edit Instruction' : 'Add Instruction' }}
                </h4>
            </div>

            <form method="POST"
                action="{{ isset($edit) ? route('instraction.update', $edit->id) : route('instraction.store') }}">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Title
                    </label>

                    <input type="text" name="title" class="form-control" placeholder="Enter Title"
                        value="{{ old('title', $edit->title ?? '') }}">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea id="editor" name="description" class="form-control" rows="10">{{ old('description', $edit->description ?? '') }}</textarea>

                </div>

                <button class="btn btn-primary">

                    @if (isset($edit))
                        Update
                    @else
                        Save
                    @endif

                </button>

            </form>

        </div>

    </div>

    <div class="card mt-4">

        <div class="card-header">

            <h5 class="mb-0">
                Instruction List
            </h5>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th width="180">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($instractions as $item)
                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->title }}</td>

                            <td>{!! Str::limit($item->description, 100) !!}</td>

                            <td>

                                <a href="{{ route('instraction.edit', $item->id) }}" class="text-info me-2">

                                    <i class="fa fa-edit"></i>

                                </a>

                                <form action="{{ route('instraction.delete', $item->id) }}" method="POST"
                                    style="display:inline-block">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="border-0 bg-transparent text-danger"
                                        onclick="return confirm('Are you sure you want to delete this item?')">

                                        <i class="fa fa-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="text-center">
                                No Record Found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
