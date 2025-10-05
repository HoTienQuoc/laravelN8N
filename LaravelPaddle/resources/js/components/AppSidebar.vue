<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, CreditCard, RefreshCw, PenTool, Users, Receipt, Book  } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const page = usePage();

const user = computed(() => page.props.auth.user);

// console.log('AppSidebar user:', user.value);

const dashboardRoute = computed(() => {
    return user.value?.role === 'user' ? '/user-dashboard' : '/dashboard';
});

const mainNavItems = computed((): NavItem[] => {
    const baseItems: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboardRoute.value,
            icon: LayoutGrid,
        },
    ];

    // Add role-specific navigation items
    if (user.value?.role === 'user') {
        baseItems.push(
            {
                title: 'Pricing',
                href: '/pricing',
                icon: CreditCard,
            },
            {
                title: 'Refund',
                href: '/refund',
                icon: RefreshCw,
            }
        );
    } else if (user.value?.role === 'admin') {
        baseItems.push(
            {
                title: 'Create Blog',
                href: '/admin/create-blog',
                icon: PenTool,
            },
            {
                title: 'View Users',
                href: '/admin/users',
                icon: Users,
            },
            {
                title: 'View Blogs',
                href: '/admin/blogs',
                icon: Book,
            }
        );
    }


    return baseItems;
});

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
