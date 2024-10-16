<?php

namespace App\Http\Controllers\Admin\Shop;


use App\Traits\QueryParams;
use Illuminate\Http\Request;
use App\Entities\Shop\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class CommentariesController extends Controller
{

    use QueryParams;

    public function index(Request $request)
    {
        $query = Comment::with('product');
        $this->queryParams($request, $query);

        $commentaries = $query->paginate(20, ['*'], 'comments');

        return view('admin.shop.comments.index', compact('commentaries'));
    }

    public function destroy (Comment $comment):RedirectResponse
    {
        $comment->delete();
        return back()->with('success', 'Комментарий успешно удален');
    }

    public function setStatus(Request $request):RedirectResponse
    {
        $IDs    = $request->get('selected');
        $action = $request->get('action');
        if (!$IDs) {
            return back()->with('error', 'Не выбран ни один элемент');
        }
        if($comments = Comment::find($IDs)) {
            foreach ($comments as $comment) {
                if ($action == 'remove') {
                    $comment->delete();
                } else {
                    $comment->update(['status' => Comment::STATUS_ACTIVE]);
                }
            }
            return $action == 'published' ? back()->with('success', count($comments) > 1 ? 'Все комментарии успешно опубликованы' : 'Комментарий успешно опубликован') :
                back()->with('success', count($comments) > 1 ? 'Все комментарии успешно удалены' : 'Комментарий успешно удален');
        }
        return back()->with('error', 'Не найден ни один комментарий');
    }
}
