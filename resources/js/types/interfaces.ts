import { PageProps } from "@inertiajs/core";

export interface Page extends PageProps {
    auth: { user: User };
}

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: null;
    created_at: string;
    updated_at: string;
}

export interface Course {
    id: number;
    title: string;
    description: string;
    slug: string;
    price: string;
    chapters: Chapter[];

    created_at: string;
    updated_at: string;
}

export interface Chapter {
    id: number;
    course_id: number;
    title: string;
    content: string;
    sort_order: number;
    created_at: string;
    updated_at: string;
    lessons: Lesson[];
}

export interface Lesson {
    id: number;
    chapter_id: number;
    course_id: number;
    title: string;
    content: string;
    sort_order: number;
    chapter: Chapter;
    created_at: string;
    updated_at: string;
}
