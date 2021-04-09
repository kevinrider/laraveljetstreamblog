<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        Welcome {{ user.name }},<br>
                        You have made {{ post_count }} {{ plural('Post',post_count) }}.
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        components: {
            AppLayout
        },
        props: {
            post_count: Number,
            user: Object,
        },
        methods: {
            plural(item,count){
                if(count > 1 || count == 0) {
                    item += "s";
                }
                return item;
            }
        }
    }
</script>
