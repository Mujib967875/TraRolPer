@extends('front.layout.master')

@section('main_content')

<style>
    .hidden {
        display: none;
    }
</style>

@php
   $setting = App\Models\Setting::where('id', 1)->first();
@endphp
    <div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>{{ $post->title }}</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('blog') }}">Blog</a></li>
                        <li class="breadcrumb-item active">{{ $post->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="post pt_50 pb_50">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 col-md-12">
                    <div class="left-item" style="margin-bottom: 150px">
                        <div class="main-photo">
                            <img src="{{ asset('uploads/'.$post->photo) }}" alt="">
                        </div>
                        <div class="sub">
                            <ul>
                                <li><i class="fas fa-calendar-alt"></i> Di posting pada: {{ $post->created_at->format('d F, Y') }}</li>
                                <li><i class="fas fa-th-large"></i> Kategori: <a href="{{ route('category',$post->blog_category->slug) }}"> {{ $post->blog_category->name }} </a></li>
                            </ul>
                        </div>
                        <div class="description">
                            <p>
                                {!! $post->description !!}
                            </p>
                        </div>
                    </div>


                    <div class="comment-section mb-5">
                        <h3 class="mb-3" style="margin-bottom: 150px">Komentar ( {{ $post->comments->where('parent_id', null)->where('status','approved')->count() }} )
                        </h3>

                        @php
                            $commentCount = $post->comments->where('parent_id', null)->where('status', 'approved')->count();
                        @endphp

                        @foreach ($post->comments->where('parent_id', null)->where('status','approved') as $index => $comment)
                            <div class="comment mt-5 {{ $index >= 15 ? 'hidden' : '' }}">
                                <strong>{{ $comment->name }}</strong>
                                <p>{{ $comment->comment }}</p>

                                <div class="d-flex justify-content-end mb-3">
                                    <button class="btn btn-sm border-primary border border-2 reply-btn mx-3" data-bs-toggle="modal" data-bs-target="#replyModal"
                                            data-comment-id="{{ $comment->id }}" data-comment-name="{{ $comment->name }}">
                                        Balas
                                    </button>

                                    @if ($comment->replies->count() > 0)
                                        <button class="btn btn-sm btn-secondary show-replies-btn" onclick="toggleReplies({{ $comment->id }})">
                                            Lihat Balasan ({{ $comment->replies->where('status','approved')->count() }})
                                        </button>
                                    @endif
                                </div>

                                @php
                                    $replyCount = $comment->replies->where('status', 'approved')->count();
                                @endphp

                                <div id="replies-{{ $comment->id }}" class="replies bg-secondary-subtle" style="display: none; margin-left: 30px;">
                                    @foreach ($comment->replies->where('status', 'approved') as $index => $reply)
                                        <div class="reply mb-3 border-top border-bottom p-3 bg-light {{ $index >= 5 ? 'hidden' : '' }}">
                                            <strong>{{ $reply->name }}</strong>
                                            <p>{{ $reply->comment }}</p>
                                        </div>
                                    @endforeach
                                
                                    @if ($replyCount > 5)
                                        <div class="text-center mt-3">
                                            <button class="btn btn-sm border-primary border border-2 show-more-replies"
                                                    data-comment-id="{{ $comment->id }}"
                                                    style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                                Lihat balasan lainnya
                                            </button>
                                        </div>
                                    @endif
                                </div>  

                                <hr>
                            </div>
                        @endforeach

                        @if ($commentCount > 15)
                        <div class="text-center mt-3">
                            <button id="showMoreComments" class="btn btn-sm border-primary border border-2">Lihat Komentar lainnya</button>
                        </div>
                        @endif

                    </div>

                    <h3 class="mb-3">Masukan Komentar</h3>
                    @auth
                    <form action="{{ route('comment.store', $post->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea name="comment" placeholder="Masukan Komentar disini..." class="form-control" style="height: 120px" required></textarea>
                        </div>
                        <button type="submit" class="btn border-primary border border-2 mt-2">Kirim</button>
                    </form>
                    @else
                        <p>Silakan <a class="btn btn-outline-primary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" href="{{ route('login') }}">login</a> untuk membalas komentar.</p>
                    @endauth

                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="right-item">
                        <h2>Postingan Terbaru</h2>
                        <ul>
                            @foreach ($latest_posts as $item)
                            <li><a href="{{ route('post', $item->slug) }}"><i class="fas fa-angle-right"></i> {{ $item->title }}</a></li>
                            @endforeach
                        </ul>

                        <h2 class="mt_40">Kategori</h2>
                        <ul>
                            @foreach ($categories as $category)
                            <li><a href="{{ route('category',$category->slug) }}"><i class="fas fa-angle-right"></i> {{ $category->name }}</a></li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">Balas Komentar <span  id="modal-comment-name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @auth
                    <form action="{{ route('reply.store', $post->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" id="modal-parent-id">
                        <div class="form-group">
                            <textarea name="comment" class="form-control" style="height: 120px" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success mt-2">Kirim</button>
                    </form>
                @else
                    <p>Silakan <a href="{{ route('login') }}">login</a> untuk membalas komentar.</p>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>

document.addEventListener("DOMContentLoaded", function () {
    let comments = document.querySelectorAll(".comment.hidden");
    let showMoreBtn = document.getElementById("showMoreComments");

    if (showMoreBtn) {
        showMoreBtn.addEventListener("click", function () {
            let shown = 0;

            comments.forEach((comment, index) => {
                if (shown < 5) {
                    comment.classList.remove("hidden");
                    comment.style.opacity = "0";
                    comment.style.transition = "opacity 0.5s ease-in-out";
                    setTimeout(() => {
                        comment.style.opacity = "1";
                    }, 50);
                    shown++;
                }
            });

            comments = Array.from(comments).slice(shown);

            if (comments.length === 0) {
                showMoreBtn.style.display = "none";
            }
        });
    }

    document.querySelectorAll(".show-more-replies").forEach(button => {
        button.addEventListener("click", function () {
            let commentId = this.getAttribute("data-comment-id");
            let replies = document.querySelectorAll(`#replies-${commentId} .reply.hidden`);
            let shown = 0;

            replies.forEach((reply, index) => {
                if (shown < 3) {
                    reply.classList.remove("hidden");
                    reply.style.opacity = "0";
                    reply.style.transition = "opacity 0.5s ease-in-out";
                    setTimeout(() => {
                        reply.style.opacity = "1";
                    }, 50);
                    shown++;
                }
            });
            
            replies = Array.from(replies).slice(shown);
            
            if (replies.length === 0) {
                this.style.display = "none";
            }
        });
    });
});

    $(document).ready(function() {
        $('#replyModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var commentId = button.data('comment-id');
            var commentName = button.data('comment-name');

            var modal = $(this);
            modal.find('#modal-parent-id').val(commentId);
            modal.find('#modal-comment-name').text(commentName);
        });

        window.toggleReplies = function(commentId) {
            let replies = document.getElementById("replies-" + commentId);
            replies.style.display = replies.style.display === "none" ? "block" : "none";
        };
    });

</script>



@endsection
