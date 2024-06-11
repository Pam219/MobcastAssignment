<!DOCTYPE html>
<html>
<head>
    <title>RSS Feed</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
    <style>
   
.description-content {
    max-height: 200px;
    overflow: auto;
}
.description-content img {
    max-width: 100%; /* Ensures the image does not exceed the container width */
    height: 100px;   /* Maintains the aspect ratio */
}
/* Set the size of pagination buttons */
.form-control{
    width:60%;
    margin-top:10px;
    margin-bottom:10px;
}
.form-inline {
  display: flex;
  flex-flow: row wrap;
  align-items: center;
}

/* Add some margins for each label */
.form-inline label {
  margin: 5px 10px 5px 0;
}

/* Style the input fields */
.form-inline input {
  vertical-align: middle;
  margin: 5px 10px 5px 0;
  padding: 10px;
  background-color: #fff;
  border: 1px solid #ddd;
}

/* Style the submit button */
.form-inline button {
  padding: 10px 20px;
  background-color: dodgerblue;
  border: 1px solid #ddd;
  color: white;
  margin-right:10px;
}

.form-inline button:hover {
  background-color: royalblue;
}

/* Add responsiveness - display the form controls vertically instead of horizontally on screens that are less than 800px wide */
@media (max-width: 800px) {
  .form-inline input {
    margin: 10px 0;
  }

  .form-inline {
    flex-direction: column;
    align-items: stretch;
  }
}

   </style>
</head>
<body> 
<div class="container mt-5">
<h2 class="mb-4">Times of India</h2>   
<form method="GET" action="{{ url('/') }}">
        <div class="form-inline ">
            <input type="text" name="search" class="form-control" placeholder="Search..." value='{{ $search }}'>
            <button type="submit" class="btn btn-primary">Search</button>              
    </form>
    <form method="GET" action="{{ url('/') }}">
            <select name="sort" class="form-control">
                <option value="title" {{ $sort === 'title' ? 'selected' : '' }}>Sort by Title</option>
                <option value="link" {{ $sort === 'link' ? 'selected' : '' }}>Sort by Link</option>
                <option value="dc:creator" {{ $sort === 'dc:creator' ? 'selected' : '' }}>Sort by Creator</option>
                <option value="pubDate" {{ $sort === 'pubDate' ? 'selected' : '' }}>Sort by Publication Date</option>
            </select>
            <input type="hidden" name="search" value="{{ $search }}">
            <button type="submit" class="btn btn-primary">Sort</button>
    </form>
</div>
    {{$data->links('pagination::bootstrap-4')}}
<table class="table table-bordered mt-4" id="rssTable">
    <!-- Table Header -->
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Link</th>
            <th>Creator</th>
            <th>Publication Date</th>
        </tr>
    </thead>
    <!-- Table Body -->
    <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{ $item['title'] }}</td>
                <td class="description-content">{!! $item['description'] !!}</td>
                <td><a href="{{ $item['link'] }}" target="_blank">View Article</a></td>
                <td>{{ $item['dc:creator'] ?? 'N/A' }}</td>
                <td>{{ $item['pubDate'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{$data->links('pagination::bootstrap-4')}}

</div>


</body>
</html>
