@if ($errors->any())
 <div class="card-text text-left alert alert-danger">
    <ul class="md-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
 </div>
 @endif