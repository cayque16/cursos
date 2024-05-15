<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use Core\Domain\Enum\Rating;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\DTO\Video\ListVideoInputDto;
use Core\UseCase\DTO\Video\ListVideos\ListVideosInputDto;
use Core\UseCase\Video\CreateVideoUseCase;
use Core\UseCase\Video\ListVideosUseCase;
use Core\UseCase\Video\ListVideoUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VideoController extends Controller
{
    public function index(Request $request, ListVideosUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListVideosInputDto(
                filter: $request->filter ?? '',
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15),
            )
        );

        return VideoResource::collection(collect($response->items))
                                    ->additional([
                                        'meta' => [
                                            'total' => $response->total,
                                            'current_page' => $response->current_page,
                                            'last_page' => $response->last_page,
                                            'first_page' => $response->first_page,
                                            'per_page' => $response->per_page,
                                            'to' => $response->to,
                                            'from' => $response->from,
                                        ]
                                    ]);
    }

    public function show(ListVideoUseCase $useCase, $id)
    {
        $response = $useCase->execute(new ListVideoInputDto($id));
        
        return new VideoResource($response);
    }

    public function store(CreateVideoUseCase $useCase, Request $request)
    {
        $response = $useCase->execute(new CreateVideoInputDto(
            title: $request->title,
            description: $request->description,
            yearLaunched: $request->year_launched,
            duration: $request->duration,
            opened: $request->opened,
            rating: Rating::from($request->rating),
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
        ));

        return (new VideoResource($response))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }
}
