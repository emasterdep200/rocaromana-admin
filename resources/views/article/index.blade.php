@extends('layouts.main')

@section('title')
{{ __('Article') }}
@endsection

@section('page-title')
<div class="page-title">
	<div class="row">
		<div class="col-12 col-md-6 order-md-1 order-last">
			<h4>@yield('title')</h4>
		</div>
		<div class="col-12 col-md-6 order-md-2 order-first article_header">
			<form action="{{ url('article_list') }}" method="GET" id="search_form">
				<input type="text" class="form-control order-first" placeholder="Search" id="search_input" name="search">
			</form>
            <div>
                <a href="{{url('article') }}" class="btn icon btn-danger delete_btn" title="{{ __('Clear Search') }}">
                    <i class="bi bi-x-circle delete_icon"></i>
                </a>
            </div>

			<a href="{{url('add_article') }}" class="btn btn-primary btn_add">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
					class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
					<path
						d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z">
					</path>
				</svg>
				{{ __('Add Article') }}
			</a>
		</div>
	</div>
</div>
@endsection


@section('content')
<section class="section">
	<div class="row">

		@foreach ($articles as $key => $value)
		<div class="col-md-3">

			<div class="card article_card">
				<img src="{{ $value->image }}" class="article_img" alt="">
				<div class="article_title mt-2">
					<b> {{ $value->title }}</b>
				</div>
				<div class="article_description">
					@php
					echo Str::substr(strip_tags($value->description), 0, 150) . '...';
					@endphp
				</div>
				<hr style="border: 1px dashed;">
				<div class="row mb-1">
					<div class="col-md-6 ">
						<div class="article_date_posted">
							Posted On
						</div>
						<div class="article_date">
							{{ date('d M Y', strtotime($value->created_at)) }}
						</div>
					</div>
					<div class="col-md-6 text-end" id="article_action">
						<a href="{{ route('article.edit', $value->id) }}" id="edit_btn"
							class="btn icon btn-primary btn-sm rounded-pill mt-2" title="Edit"><i
								class="fa fa-edit edit_icon"></i></a>
						&nbsp;&nbsp;

						<a href="{{ route('article.destroy', $value->id) }}" id="delete_btn"
							onclick="return confirmationDelete(event);"
							class="btn icon btn-danger btn-sm rounded-pill mt-2 delete_btn" data-bs-toggle="tooltip"
							data-bs-custom-class="tooltip-dark" title="Delete"><i
								class="bi bi-trash delete_icon"></i></a>

					</div>
				</div>

			</div>

		</div>
		@endforeach

	</div>
	@if (count($articles)>12)

	<div class="row d-flex text-center mb-0">

		<form action="{{ url('article_list') }}" method="GET">
			<input type="hidden" name="limit" value="1">
			<button id="show_more" class="link-button">Show More</button>
		</form>

	</div>
	@endif

</section>
@endsection

@section('script')
<script>
 $('#show_more').on('click', function() {
            $('#get_limit').val('all');
            //get_articles("",2);
        });
        $('#search_input').on('input', function() {
            var searchValue = $(this).val();
            $('#search_form').submit();

        });
</script>
@endsection
