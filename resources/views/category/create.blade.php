@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Create Category</h2>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="col-md-12">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
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
                <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-plus-circle"></i> Add Category</button>
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
                    "X-Requested-With": "XMLHttpRequest", // Ensures Laravel recognizes it as an AJAX request
                    "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Category Added!",
                        text: "The category has been successfully added.",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    form.reset();

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
                    text: "Failed to add category. Please try again.",
                });
            });
        });
    });
</script>

@endsection
