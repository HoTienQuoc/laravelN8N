<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Switch } from "@/components/ui/switch";
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { Check, Star, Zap } from 'lucide-vue-next';
import { computed, ref, onMounted } from 'vue';
import { initializePaddle, Paddle } from '@paddle/paddle-js';

// Initialize Paddle instance
const paddle = ref<Paddle | null>(null);

const page = usePage();
const user = computed(() => page.props.auth.user);


// Initialize Paddle on component mount
onMounted(async () => {
    try {
        const paddleInstance = await initializePaddle({
            environment: 'production', // Change to 'production' for live environment
            token: 'live_77c7752ca61bf2c7f554fd1df25' // Replace with your actual auth token
        });
        if (paddleInstance) {
            paddle.value = paddleInstance;
        }
    } catch (error) {
        console.error('Failed to initialize Paddle:', error);
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/user-dashboard',
    },
    {
        title: 'Pricing',
        href: '/pricing',
    },
];

// Reactive pricing toggle
const isYearly = ref(false);

// Define plan type
interface Plan {
    id: string;
    name: string;
    description: string;
    monthlyPrice: number;
    monthlyPriceID: string;
    yearlyPriceID: string;
    yearlyPrice: number;
    icon: any; // Component type
    popular: boolean;
    features: string[];
}



// Pricing plans
// Pricing plans
const plans: Plan[] = [
    {
        id: 'basic',
        name: 'Basic Plan',
        description: 'Perfect for individuals getting started',
        monthlyPriceID: 'pri_01k6s17449b3em6khce87bkzb8',
        yearlyPriceID: 'pri_01k6s1dd0xk04z2ahjans6jfm5',
        monthlyPrice: 10,
        yearlyPrice: 108,
        icon: Star,
        popular: false,
        features: ['Access to all basic Blogs', 'Mobile app access'],
    },
    {
        id: 'premium',
        name: 'Premium Plan',
        description: 'Best for professionals and growing teams',
        monthlyPrice: 15,
        monthlyPriceID: 'pri_01k6s2prqh238npnvd66qp7520',
        yearlyPriceID: 'pri_01k6s2nhtwyzbz79p3mhv32x3f',
        yearlyPrice: 144,
        icon: Zap,
        popular: true,
        features: ['Access to all Blogs', 'Mobile app access'],
    },
];


// Helper functions (not computed properties)
const getPrice = (plan: Plan): number => {
    return isYearly.value ? plan.yearlyPrice : plan.monthlyPrice;
};

const getSavings = (plan: Plan): number => {
    const yearlyTotal = plan.monthlyPrice * 12;
    return yearlyTotal - plan.yearlyPrice;
};

const getSavingsPercentage = (plan: Plan): number => {
    const yearlyTotal = plan.monthlyPrice * 12;
    const savings = yearlyTotal - plan.yearlyPrice;
    return Math.round((savings / yearlyTotal) * 100);
};

// Computed property for maximum savings percentage
const maxSavingsPercentage = computed(() => {
    return Math.max(...plans.map((plan) => getSavingsPercentage(plan)));
});

// Subscribe function with error handling
const subscribe = (planId: string, isYearlyPlan: boolean) => {
    try {
        const plan = plans.find((p) => p.id === planId);
        if (!plan) {
            console.error('Plan not found:', planId);
            return;
        }

        // Check if Paddle is initialized
        if (!paddle.value) {
            console.error('Paddle is not initialized yet');
            alert('Payment system is still loading. Please try again in a moment.');
            return;
        }

        console.log(`Subscribing to ${plan.name}`);
        console.log(`Billing: ${isYearlyPlan ? 'Yearly' : 'Monthly'}`);
        console.log(`Price: $${getPrice(plan)}`);

        // Get the correct price ID based on billing cycle
        const priceId = isYearlyPlan ? plan.yearlyPriceID : plan.monthlyPriceID;

        // Open Paddle checkout
        paddle.value.Checkout.open({
            items: [
                {
                    priceId: priceId,
                    quantity: 1
                }
            ],
            settings: {
                displayMode: "overlay",
            },
            customData: {
                email: user.value?.email || '',
                userId: user.value?.id || '',
            },
        });


        alert(`Redirecting to payment for ${plan.name} (${isYearlyPlan ? 'Yearly' : 'Monthly'}: $${getPrice(plan)})`);
    } catch (error) {
        console.error('Error subscribing:', error);
        alert('An error occurred while processing your subscription. Please try again.');
    }
};


</script>

<template>
    <Head title="Pricing Plans" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-8 rounded-xl p-6">
            <!-- Header Section -->
            <div class="text-center">
                <h1 class="text-3xl font-bold tracking-tight">Choose Your Plan</h1>
                <p class="mt-2 text-muted-foreground">Select the perfect plan for your needs. Upgrade or downgrade at any time.</p>
            </div>

            <!-- Billing Toggle -->
            <div class="flex items-center justify-center gap-4">
                <span :class="['text-sm font-medium', !isYearly ? 'text-foreground' : 'text-muted-foreground']"> Monthly </span>
                <Switch id="monthlyYearly" v-model="isYearly" />
                <span :class="['text-sm font-medium', isYearly ? 'text-foreground' : 'text-muted-foreground']"> Yearly </span>
                <Badge v-if="isYearly" variant="secondary" class="ml-2 text-xs"> Save up to {{ maxSavingsPercentage }}% </Badge>
            </div>

            <!-- Pricing Cards -->
            <div class="mx-auto grid w-full max-w-4xl grid-cols-1 gap-8 lg:grid-cols-2">
                <Card
                    v-for="plan in plans"
                    :key="plan.id"
                    :class="[
                        'relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg',
                        plan.popular ? 'border-primary shadow-lg' : '',
                    ]"
                >
                    <!-- Popular Badge -->
                    <div v-if="plan.popular" class="absolute -top-3 left-1/2 z-10 -translate-x-1/2 transform">
                        <Badge class="mt-3 bg-primary px-4 py-1 text-primary-foreground shadow-md"> Most Popular </Badge>
                    </div>

                    <CardHeader class="pt-8 pb-6 text-center">
                        <!-- Plan Icon -->
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                            <component :is="plan.icon" class="h-8 w-8 text-primary" />
                        </div>

                        <CardTitle class="text-2xl font-bold">{{ plan.name }}</CardTitle>
                        <CardDescription class="text-sm">{{ plan.description }}</CardDescription>

                        <!-- Pricing -->
                        <div class="mt-6">
                            <div class="flex items-baseline justify-center gap-1">
                                <span class="text-4xl font-bold">${{ getPrice(plan) }}</span>
                                <span class="text-muted-foreground">/ {{ isYearly ? 'year' : 'month' }}</span>
                            </div>

                            <!-- Yearly savings -->
                            <div v-if="isYearly" class="mt-2">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-sm text-muted-foreground line-through"> ${{ plan.monthlyPrice * 12 }} </span>
                                    <Badge variant="secondary" class="text-xs"> Save {{ getSavingsPercentage(plan) }}% </Badge>
                                </div>
                                <span class="text-sm font-medium text-green-600"> You save ${{ getSavings(plan) }} per year </span>
                            </div>
                        </div>
                    </CardHeader>

                    <CardContent class="px-6">
                        <!-- Features List -->
                        <ul class="space-y-3">
                            <li v-for="feature in plan.features" :key="feature" class="flex items-center gap-3">
                                <Check class="h-4 w-4 flex-shrink-0 text-green-600" />
                                <span class="text-sm">{{ feature }}</span>
                            </li>
                        </ul>
                    </CardContent>

                    <CardFooter class="px-6 pb-6">
                        <Button
                            @click="subscribe(plan.id, isYearly)"
                            :class="[
                                'w-full transition-colors',
                                plan.popular ? 'bg-primary hover:bg-primary/90' : 'variant-outline hover:bg-secondary',
                            ]"
                            :variant="plan.popular ? 'default' : 'outline'"
                            size="lg"
                        >
                            Get Started
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <!-- Additional Information -->
            <div class="mx-auto max-w-2xl space-y-4 text-center">
                <div class="grid grid-cols-1 gap-4 text-sm text-muted-foreground md:grid-cols-3">
                    <div class="flex items-center justify-center gap-2">
                        <Check class="h-4 w-4 text-green-600" />
                        <span>30-day money back guarantee</span>
                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <Check class="h-4 w-4 text-green-600" />
                        <span>Cancel anytime</span>
                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <Check class="h-4 w-4 text-green-600" />
                        <span>24/7 customer support</span>
                    </div>
                </div>

                <p class="text-xs text-muted-foreground">
                    All prices are in USD. Taxes may apply. By subscribing, you agree to our Terms of Service and Privacy Policy.
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Additional custom styles if needed */
</style>
