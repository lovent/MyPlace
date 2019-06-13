<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Repositories\Contracts\RateReviewValRepositoryInterface;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\Contracts\RateReviewRepositoryInterface;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CollectionRepositoryInterface;
use App\Repositories\Contracts\PlaceRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Http\Requests\CreateReviewRequest;
use App\Events\Notifications;
use Storage;
use Auth;
use Log;

class ReviewController extends Controller
{
    protected $reviewRepository;
    protected $imageRepository;
    protected $rateReviewValRepository;
    protected $rateReviewRepository;
    protected $commentRepository;
    protected $reportRepository;
    protected $collectionRepository;
    protected $placeRepository;
    protected $categoryRepository;
    protected $notificationRepository;

    public function __construct(
        ReviewRepositoryInterface $reviewRepository,
        ImageRepositoryInterface $imageRepository,
        RateReviewValRepositoryInterface $rateReviewValRepository,
        RateReviewRepositoryInterface $rateReviewRepository,
        CommentRepositoryInterface $commentRepository,
        CollectionRepositoryInterface $collectionRepository,
        PlaceRepositoryInterface $placeRepository,
        CategoryRepositoryInterface $categoryRepository,
        ReportRepositoryInterface $reportRepository,
        NotificationRepositoryInterface $notificationRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->imageRepository = $imageRepository;
        $this->placeRepository = $placeRepository;
        $this->rateReviewValRepository = $rateReviewValRepository;
        $this->rateReviewRepository = $rateReviewRepository;
        $this->commentRepository = $commentRepository;
        $this->reportRepository = $reportRepository;
        $this->categoryRepository = $categoryRepository;
        $this->collectionRepository = $collectionRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function create()
    {
        $category = $this->categoryRepository->getParent();
        $cateChild = [];
        foreach ($category as $value) {
            $cateChild[$value->id] = $this->categoryRepository->getChild($value->id);
        }

        return view('frontend.review.new-review', compact('category', 'cateChild'));
    }

    public function store(CreateReviewRequest $request)
    {
        try {
            if ($request->hasFile('file')) {
                $data = [];
                foreach ($request->file('file') as $file) {
                    $nameImage = str_random(6) . $file->getClientOriginalName();
                    array_push($data, $nameImage);
                    $file->move(public_path('images/postreview'), $nameImage);
                }
            }
            $dataValue = $request->only(
                'submary',
                'content',
                'timewrite',
                'service_rate',
                'quality_rate',
                'place_id'
            );
            $dataValue['user_id'] = Auth::user()->id;
            $dataValue['status'] = config('checkbox.checktrue');
            $resultReview = $this->reviewRepository->create($dataValue);
            $reviewId = $resultReview->id;
            $requestImage = $this->imageRepository->create($data, $reviewId);
            $dataPlace['place_id'] = $dataValue['place_id'];
            $dataPlace['service_rate'] = $dataValue['service_rate'];
            $dataPlace['quality_rate'] = $dataValue['quality_rate'];
            $resultPlace = $this->placeRepository->updateRate($dataPlace);

            return redirect()->route('home');
        } catch (Exception $e) {
            Log::error($e);

            return back()->withErrors(trans('messages.updatefail'));
        }
    }

    public function show($id)
    {
        $review = $this->reviewRepository->find($id);
        $countLike = 0;
        $countComment = 0;
        $showComment = $this->commentRepository->findReviewId($id);
        $rateReviewVal = $this->rateReviewValRepository->all();
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $hasLike = $this->rateReviewValRepository->findReviewID($id, $userId);
            $hasReport = $this->reportRepository->findReport($id, $userId);
            $collection = $this->collectionRepository->userCollection($userId);
            $checkIfInCol = $this->collectionRepository->checkIfIn($id);
            $collection_all = $this->collectionRepository->all();
        }
        $rateReview = $this->rateReviewRepository->findRateLike();
        $comments = $this->commentRepository->all();
        $rateValId = $this->rateReviewValRepository->findRate($id);
        $countComment = $this->commentRepository->getCommentNumber($id);
        $countLike = $this->rateReviewValRepository->getLikes($id);
        $like_user = $this->rateReviewValRepository->getUserLike($id);
        return view('frontend.review.show-review', compact(
            'review',
            'rateValId',
            'countLike',
            'countComment',
            'showComment',
            'rateReview',
            'hasLike',
            'rateReviewVal',
            'hasReport',
            'collection',
            'checkIfInCol',
            'like_user'
        ));
    }

    public function edit($id)
    {
        $review = $this->reviewRepository->find($id);

        return view('frontend.review.edit-review', compact('review'));
    }

    public function update(Request $request, $id)
    {
        try {
            if ($request->hasFile('file')) {
                $data = [];
                foreach ($request->file('file') as $file) {
                    $nameImage = str_random(4).date('h:i').$file->getClientOriginalName();
                    array_push($data, $nameImage);
                    $file->move(config('asset.image_path.imagereviews'), $nameImage);
                }
            } else {
                $data[0] = null;
            }
            $dataValue = $request->only(
                'submary',
                'content',
                'timewrite',
                'place_id'
            );
            if ($request->service_rate == null && $request->quality_rate == null) {
                $dataValue['service_rate'] = $request->service_rate_old;
                $dataValue['quality_rate'] = $request->quality_rate_old;
            } elseif ($request->service_rate == null && $request->quality_rate != null) {
                $dataValue['quality_rate_old'] = $request->quality_rate_old;
                $dataValue['quality_rate'] = $request->quality_rate;
                $dataValue['service_rate'] = $request->service_rate_old;
                $updateQualityRate = $this->placeRepository->updateQualityRate($dataValue, $id);
            } elseif ($request->service_rate != null && $request->quality_rate == null) {
                $dataValue['service_rate_old'] = $request->service_rate_old;
                $dataValue['service_rate'] = $request->service_rate;
                $dataValue['quality_rate'] = $request->quality_rate_old;
                $updateServiceRate = $this->placeRepository->updateServiceRate($dataValue, $id);
            } else {
                $dataValue['service_rate_old'] = $request->service_rate_old;
                $dataValue['service_rate'] = $request->service_rate;
                $dataValue['quality_rate_old'] = $request->quality_rate_old;
                $dataValue['quality_rate'] = $request->quality_rate;
                $updateRateAll = $this->placeRepository->updateRateAll($dataValue, $id);
            }
                $dataValue['user_id'] = Auth::user()->id;
                $dataValue['status'] = config('checkbox.checktrue');
                $resultReview = $this->reviewRepository->edit($dataValue, $id);
            if ($data[0] != null) {
                $requestImage = $this->imageRepository->create($data, $id);
            }

            return redirect()->route('home', $id);
        } catch (Exception $e) {
            Log::error($e);

            return back()->withErrors(trans('messages.updatefail'));
        }
    }

    public function favorite(Request $request)
    {
        $reviewId = $request->reviewId;
        $rateId = $request->rateId;
        $userId = Auth::user()->id;
        $userName = Auth::user()->name;
        $hasLike = $this->rateReviewValRepository->findReviewID($reviewId, $userId);
        $rateReviewVal = $this->rateReviewValRepository->all();
        $rateReview = $this->rateReviewRepository->findRateLike();
        if ($hasLike == config('const.hasLike')) {
            $icon = true;
            $resultReviewVal = $this->rateReviewValRepository->create($userId, $rateId, $reviewId);
            $review =  $this->reviewRepository->findNameReview($reviewId);
            $notificationData = [
                'action' => config('notification.like'),
                'content' => $userName . config('notification.content') . $review->submary,
                'status' => config('notification.notseen'),
                'review_id' => $reviewId,
                'user_id' => $userId,
            ];
            $notification = $this->notificationRepository->create($notificationData);
            event(new Notifications($notification->content, 1, Auth::user()->PathImage, $reviewId, $notification->review->user_id, $notification->id, $notification->action));
        } else {
            $icon = false;
            $resultReviewVal = $this->rateReviewValRepository->disLike($reviewId, $userId);
            $notificationId = $this->notificationRepository->findAndDelete($reviewId);
        }
        $countLike = $this->rateReviewValRepository->getLikes($reviewId);
        
        return response()->json([
           'icon' => $icon,
           'countLike' => $countLike,
           'reviewId' => $reviewId,
        ]);
    }

    public function comment(Request $request)
    {
        $content = $request->comment;
        $reviewId = $request->reviewId;
        $userId = Auth::user()->id;
        $avatarUser = Auth::user()->avatar;
        $nameUser = Auth::user()->name;
        $data = $this->commentRepository->create($content, $reviewId, $userId);
        $review =  $this->reviewRepository->findNameReview($reviewId);
        $notificationData = [
            'action' => config('notification.comment'),
            'content' => $nameUser . config('notification.contentcomment') . $review->submary,
            'status' => config('notification.notseen'),
            'review_id' => $reviewId,
            'user_id' => $userId,
        ];
        $notification = $this->notificationRepository->create($notificationData);
        event(new Notifications($notification->content, 1, Auth::user()->PathImage, $reviewId, $notification->review->user_id, $notification->id, $notification->action));
        return response(view('frontend.showcoment.show-comment', compact(
            'content',
            'data',
            'userId',
            'avatarUser',
            'nameUser',
            'reviewId'
        ))->render());
    }

    public function updateComment(Request $request)
    {
        $commentId = $request->commentId;
        $reviewId = $request->reviewId;
        $contentUpdate = $request->contentUpdate;
        $userId = Auth::user()->id;
        $resultComment = $this->commentRepository->update($commentId, $reviewId, $contentUpdate, $userId);

        return redirect()->route('reviews.show', $reviewId);
    }
    
    public function deleteComment(Request $request)
    {
        $commentId = $request->commentId;
        $resultComment = $this->commentRepository->delete($commentId);

        return response()->json([
            'commentId' => $commentId,
        ]);
    }

    public function remove(Request $request)
    {
        $id = $request->imageId;
        $resultImage = $this->imageRepository->delete($id);

        return response()->json([
            'id' => $id,
        ]);
    }

    public function removeReview(Request $request)
    {
        $idReview = $request->reviewId;
        $resultUpdate = $this->reviewRepository->update($idReview);
        $dataSuccess = 'Delete Success';

        return response()->json([
            'dataSuccess' => $dataSuccess,
        ]);
    }

    public function addToCollection($id)
    {
        $review = $this->reviewRepository->find($id);
        $collection = $this->collectionRepository->userCollection(Auth::user()->id);

        return view('frontend.add-to-collection', compact('review', 'collection'));
    }

    public function changeStatus(Request $request)
    {
        $this->notificationRepository->changeStatus($request->notificationId);
    }
}
