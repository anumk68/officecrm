@extends('layouts.app')

@section('content')

<style>
    .upload-container {
        min-height: 100vh;
        /* background: linear-gradient(145deg, #a3bcee, #6b73a0); */
        display: flex;
        justify-content: center;
        align-items: center;
       
        margin-left: 100px ;
    }

    .upload-box {
        width: 550px;
      /* background: linear-gradient(145deg, #6a91e6, #f8f9ff); */
        padding: 35px;
        border-radius: 18px;
        box-shadow: 0px 10px 35px rgba(91, 100, 226, 0.42);
        text-align: center;
        transition: 0.3s ease-in-out;
    }

    .upload-box:hover {
        transform: translateY(-5px);
        box-shadow: 0px 15px 45px rgba(0, 0, 0, 0.18);
    }

    .drag-area {
        border: 2px dashed #0d6efd;
        border-radius: 16px;
        padding: 30px;
        cursor: pointer;
        transition: 0.3s;
    }

    .drag-area:hover,
    .drag-area.active {
        background: #eef5ff;
        border-color: #0b5ed7;
    }

    .file-icon {
        font-size: 45px;
        color: #0d6efd;
    }

    #fileName {
        font-weight: 600;
        margin-top: 10px;
        color: #0d6efd;
    }
</style>

<div class="upload-container">

    <div class="upload-box">

        <h3 class="text-primary fw-bold mb-3">
            <i class="bi bi-cloud-arrow-up-fill"></i> Upload Leads File
        </h3>
        <p class="text-muted mb-4">Upload your Excel (.xls, .xlsx, .csv) and start mapping instantly.</p>

        @if ($errors->any())
            <div class="alert   text-danger text-start">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('leads.import.upload') }}" id="uploadForm" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="drag-area mb-3" id="dragArea">
                <i class="bi bi-upload file-icon"></i>
                <p class="mt-2 mb-1"><strong>Click or Drag & Drop</strong> your file here</p>
                <small class="text-muted">Supported formats: .xls, .xlsx, .csv</small>
                <input type="file" id="fileInput" name="file" hidden accept=".xls,.xlsx,.csv">
                <div id="fileName"></div>
            </div>

            <button class="btn btn-primary w-100 py-2 rounded-pill fw-semibold">
                <i class="bi bi-check-circle"></i> Upload & Map
            </button>
        </form>
    </div>

</div>

<script>
    const dragArea = document.getElementById("dragArea");
    const fileInput = document.getElementById("fileInput");
    const fileNameDisplay = document.getElementById("fileName");

    // click to open
    dragArea.addEventListener("click", () => fileInput.click());

    // file selected
    fileInput.addEventListener("change", function () {
        if (this.files.length > 0) {
            fileNameDisplay.textContent = "Selected: " + this.files[0].name;
            dragArea.classList.add("active");
        }
    });

    // drag over
    dragArea.addEventListener("dragover", (event) => {
        event.preventDefault();
        dragArea.classList.add("active");
    });

    // drag leave
    dragArea.addEventListener("dragleave", () => {
        dragArea.classList.remove("active");
    });

    // drop file
    dragArea.addEventListener("drop", (event) => {
        event.preventDefault();
        dragArea.classList.add("active");

        let file = event.dataTransfer.files[0];
        fileInput.files = event.dataTransfer.files;

        fileNameDisplay.textContent = "Selected: " + file.name;
    });
</script>

@endsection
