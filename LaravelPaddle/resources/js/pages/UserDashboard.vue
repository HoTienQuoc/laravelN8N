<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Calendar, Clock, Eye, ArrowRight } from 'lucide-vue-next';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Dashboard',
        href: '/user-dashboard',
    },
];

// Blog posts array
const blogs = [
    {
        id: 1,
        title: 'Getting Started with Vue 3 Composition API',
        body: 'Learn how to build modern Vue applications using the powerful Composition API. This comprehensive guide covers everything from basic setup to advanced patterns and best practices for scalable applications.',
        blogImage: 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=400&h=250&fit=crop',
        author: 'John Doe',
        publishedAt: '2024-01-15',
        readTime: '5 min read',
        views: 1234,
        category: 'Web Development',
        featured: true,
    },
    {
        id: 2,
        title: 'Building Responsive Layouts with Tailwind CSS',
        body: 'Master the art of creating beautiful, responsive designs with Tailwind CSS. Discover utility-first approaches, custom components, and advanced techniques for modern web design.',
        blogImage: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=400&h=250&fit=crop',
        author: 'Jane Smith',
        publishedAt: '2024-01-12',
        readTime: '8 min read',
        views: 987,
        category: 'CSS',
        featured: false,
    },
    {
        id: 3,
        title: 'JavaScript ES2024: New Features and Updates',
        body: 'Explore the latest JavaScript features and improvements in ES2024. From new array methods to enhanced async patterns, stay up-to-date with the evolving JavaScript ecosystem.',
        blogImage: 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?w=400&h=250&fit=crop',
        author: 'Mike Johnson',
        publishedAt: '2024-01-10',
        readTime: '6 min read',
        views: 756,
        category: 'JavaScript',
        featured: true,
    },
    {
        id: 4,
        title: 'Database Design Patterns for Modern Applications',
        body: 'Learn essential database design patterns that will help you build scalable and maintainable applications. Covers relational and NoSQL approaches with practical examples.',
        blogImage: 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=400&h=250&fit=crop',
        author: 'Sarah Wilson',
        publishedAt: '2024-01-08',
        readTime: '12 min read',
        views: 543,
        category: 'Database',
        featured: false,
    },
    {
        id: 5,
        title: 'API Security Best Practices',
        body: 'Comprehensive guide to securing your APIs against common vulnerabilities. Learn about authentication, authorization, rate limiting, and other critical security measures.',
        blogImage: 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=400&h=250&fit=crop',
        author: 'David Brown',
        publishedAt: '2024-01-05',
        readTime: '10 min read',
        views: 432,
        category: 'Security',
        featured: false,
    },
    {
        id: 6,
        title: 'Building Progressive Web Apps in 2024',
        body: 'Step-by-step guide to creating Progressive Web Apps that deliver native-like experiences. Learn about service workers, caching strategies, and offline functionality.',
        blogImage: 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=250&fit=crop',
        author: 'Lisa Chen',
        publishedAt: '2024-01-03',
        readTime: '15 min read',
        views: 891,
        category: 'PWA',
        featured: true,
    },
];

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const formatViews = (views: number) => {
    if (views >= 1000) {
        return `${(views / 1000).toFixed(1)}k`;
    }
    return views.toString();
};

const readBlog = (blogId: number) => {
    // Handle blog reading logic here
    console.log(`Reading blog with ID: ${blogId}`);
    // You can navigate to blog detail page or open modal
};

</script>

<template>
    <Head title="User Dashboard - Blog" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Blog Posts</h1>
                    <p class="text-muted-foreground">Discover insights, tutorials, and latest updates from our blog</p>
                </div>
            </div>

            <!-- Blog Cards Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="blog in blogs"
                    :key="blog.id"
                    class="group relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                >
                    <!-- Blog Image -->
                    <div class="relative aspect-video overflow-hidden">
                        <img
                            :src="blog.blogImage"
                            :alt="blog.title"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                        />

                        <!-- Featured Badge -->
                        <div class="absolute top-3 left-3">
                            <Badge v-if="blog.featured" variant="default" class="text-xs">Featured</Badge>
                        </div>

                        <!-- Category Badge -->
                        <div class="absolute top-3 right-3">
                            <Badge variant="secondary" class="text-xs">{{ blog.category }}</Badge>
                        </div>

                        <!-- Gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                    </div>

                    <!-- Blog Content -->
                    <CardHeader class="pb-3">
                        <CardTitle class="line-clamp-2 text-xl font-bold leading-tight">
                            {{ blog.title }}
                        </CardTitle>
                        <CardDescription class="line-clamp-3 text-sm leading-relaxed">
                            {{ blog.body }}
                        </CardDescription>
                    </CardHeader>

                    <CardContent class="pb-4">
                        <!-- Blog Meta Information -->
                        <div class="flex flex-col gap-3">
                            <!-- Author and Date -->
                            <div class="flex items-center gap-4 text-sm text-muted-foreground">
                                <div class="flex items-center gap-1">
                                    <Calendar class="h-4 w-4" />
                                    <span>{{ formatDate(blog.publishedAt) }}</span>
                                </div>
                                <span>by {{ blog.author }}</span>
                            </div>

                            <!-- Read time and Views -->
                            <div class="flex items-center justify-between text-sm text-muted-foreground">
                                <div class="flex items-center gap-1">
                                    <Clock class="h-4 w-4" />
                                    <span>{{ blog.readTime }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Eye class="h-4 w-4" />
                                    <span>{{ formatViews(blog.views) }} views</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>

                    <CardFooter class="pt-0">
                        <Button
                            @click="readBlog(blog.id)"
                            class="w-full group-hover:bg-primary/90 transition-colors"
                        >
                            Read Article
                            <ArrowRight class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" />
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <!-- Load More Section -->
            <div class="flex justify-center pt-6">
                <Button variant="outline" size="lg">
                    Load More Articles
                </Button>
            </div>
        </div>
    </AppLayout>

</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

