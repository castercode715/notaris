<div class="box-body" id="comment-wrapper">
    @if(count($commentList) != 0)
        @foreach($commentList as $list)
        <div class="post" style="padding-bottom: 25px;">
            <div class="user-block">
                <img class="img-circle img-bordered-sm" src="/images/investor/{{ $list->photo }}" alt="user image">
                <span class="username">
                    <a href="#">{{ $list->investor }}</a>
                    <div class="pull-right btn-box-tool">
                        <a href="{{ route('news.comment.approve', $list->id) }}" class="btn btn-xs btn-primary btn-comment-approve" id="comment-approve-{{ $list->id }}" data-id="{{ $list->id }}" style="display: {{ $list->is_visible==1 ? 'none':''  }};">
                            <i class="fa fa-check"></i>
                        </a>
                        <a href="{{ route('news.comment.reject', $list->id) }}" class="btn btn-xs btn-danger btn-comment-reject" id="comment-reject-{{ $list->id }}" data-id="{{ $list->id }}" style="display: {{ $list->is_visible==0 ? 'none':''  }};">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </span>
                <span class="description">Shared publicly - {{ $uti->localDate($list->created_at) }}</span>
            </div>
            <p>{{ $list->comment }}</p>
            <div class="box-comment-child">
                <div class="box-tools">
                    <a href="{{ route('news.comment.child', $list->id) }}" class="link-black text-sm show-child" id="show-comment-{{$list->id}}" data-id="{{$list->id}}" style="display: {{ $comment->count($list->id)==0?'none':'' }};"><i class="fa fa-comments-o margin-r-5" ></i> Show Comments({{ $comment->count($list->id) }})</a>
                    <a href="javascript:void(0)" class="link-black text-sm hide-child" style="display: none;" id="hide-comment-{{$list->id}}" data-id="{{$list->id}}"><i class="fa fa-comments-o margin-r-5"></i> Hide Comments</a>
                </div>
                <div id="wrapper-{{ $list->id }}"></div>
                {{-- <div class="post" style="padding-bottom: 25px; margin-left:25px; margin-top:10px;">
                    <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="/images/investor/{{ $list->photo }}" alt="user image">
                        <span class="username">
                            <a href="#">{{ $list->investor }}</a>
                            <div class="pull-right btn-box-tool">
                                <a href="" class="btn btn-xs btn-primary btn-approve" data-id="{{ $list->id }}"><i class="fa fa-check"></i></a>
                                <a href="" class="btn btn-xs btn-danger btn-reject" data-id="{{ $list->id }}"><i class="fa fa-times"></i></a>
                            </div>
                        </span>
                        <span class="description">Shared publicly - {{ $uti->localDate($list->created_at) }}</span>
                    </div>
                    <p>{{ $list->comment }}</p>
                    <div class="box-comment-child">
                        <div class="box-tools">
                            <a href="#" class="link-black text-sm show-child" data-id="{{ $list->id }}"><i class="fa fa-comments-o margin-r-5"></i> Comments({{ $comment->count($list->id) }})</a>
                        </div>
                        
                    </div>
                </div> --}}
            
            </div>
        </div>
        @endforeach
    @else
        <div class="callout callout-info">
            <h4>Comment not found!</h4>
        </div>
    @endif
</div>