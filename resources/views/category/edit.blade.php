@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Edit Category</h2>

        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $category->name }}" required>
                </div>
                <div class="col-md-12">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea id="description" name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                </div>
                <div class="col-md-12">
                    <label for="service" class="form-label">Service</label>
                    <select id="service" name="service" class="form-control" required>
                        <option value="" disabled selected>Select a service</option>
                        <option value="IT">IT</option>
                        <option value="Marketing">Marketing</option>
                    </select>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Update Category</button>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form");

        form.addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(form);

            fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Category Updated!",
                            text: "The category has been successfully updated.",
                            showConfirmButton: false,
                            timer: 1500
                        });

                        setTimeout(() => {
                            window.location.href = "{{ route('categories.index') }}"; // Redirect to category list
                        }, 1500);

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: data.message || "Something went wrong!",
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Failed to update category. Please try again.",
                    });
                });
        });
    });
</script>

@endsection