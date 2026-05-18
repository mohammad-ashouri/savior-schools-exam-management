Questions <br/>
<form action="{{ route('excel.importQuestions') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file" required>
    <button type="submit">آپلود فایل</button>
</form>

SubQuestions <br/>
<form action="{{ route('excel.importSubQuestions') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file" required>
    <button type="submit">آپلود فایل</button>
</form>
@if(session('success')) <p>{{ session()->get('success') }}</p> @endif