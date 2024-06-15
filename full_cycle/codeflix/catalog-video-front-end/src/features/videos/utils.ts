import { Video, VideoPayload } from "../../types/Video";

export function mapVideoToForm(video: Video): VideoPayload {
    return {
        id: video.id,
        title: video.title,
        rating: video.rating,
        opened: video.opened,
        duration: video.duration,
        description: video.description,
        year_launched: video.year_launched,
        genres: video.genres?.map((genre) => genre.id),
        categories: video.categories?.map((category) => category.id),
        cast_members: video.cast_members?.map((cast_member) => cast_member.id),
    };
}
