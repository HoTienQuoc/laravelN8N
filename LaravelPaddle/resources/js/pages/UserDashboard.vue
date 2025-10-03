<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Dashboard',
        href: '/user-dashboard',
    },
];
</script>

<template>
    <Head title="User Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">

    </AppLayout>

<H1>fafas</H1>
</template>

