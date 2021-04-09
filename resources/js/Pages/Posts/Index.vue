<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                All Posts
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <flash-messages v-show="showFlashMsg"/>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center bg-gray-200 rounded">
                            <div>
                                <button type="button" @click="showModal" class="border border-green-500 bg-green-500 text-xs text-white rounded-md p-2 m-2 transition duration-500 ease select-none shadow hover:bg-green-800 focus:outline-none focus:shadow-outline">
                                Create New Post
                                </button> 
                            </div>
                            <div class="pr-2">
                                <button type="button" @click="pullRss" class="flex items-center border border-yellow-400 bg-yellow-400 text-xs text-white rounded-md p-2 m-2 transition duration-500 ease select-none shadow hover:bg-yellow-500 focus:outline-none focus:shadow-outline">
                                <half-circle-spinner class="mr-2" v-if="loading" :animation-duration="1000" :size="15" color="#fff"/><span>Populate from Google RSS News Feed</span>
                                </button>
                            </div>
                            <div class="flex items-center rounded shadow">
                                <div class="flex items-baseline bg-white border-r rounded-l">
                                    <svg class="h-6 w-6 m-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.822 20.88l-6.353-6.354c.93-1.465 1.467-3.2 1.467-5.059.001-5.219-4.247-9.467-9.468-9.467s-9.468 4.248-9.468 9.468c0 5.221 4.247 9.469 9.468 9.469 1.768 0 3.421-.487 4.839-1.333l6.396 6.396 3.119-3.12zm-20.294-11.412c0-3.273 2.665-5.938 5.939-5.938 3.275 0 5.94 2.664 5.94 5.938 0 3.275-2.665 5.939-5.94 5.939-3.274 0-5.939-2.664-5.939-5.939z"/></svg>
                                </div>
                                <input v-model="search" class="w-full py-1 border-0 rounded-r" autocomplete="off" type="text" placeholder="Title Search…" ref="searchInput" />
                            </div>
                        </div>
                        <template v-if="posts.data.length > 0">
                            <div v-for="post in posts.data" :key="post.id" class='p-4 border-b border-gray-200'>
                                <h2 class='text-xl font-medium underline'>{{ post.title }}</h2>
                                <p class='text-gray-600 leading-relaxed'>{{ strippedBody(post.body) }}</p>
                                <div class="flex items-center bg-gray-200 rounded w-min">
                                    <div class="m-1">
                                        <button type="button" @click="showEditModal(post.id,post.title,post.body)" class="border border-indigo-500 bg-indigo-500 text-xs text-white shadow rounded-md px-1 py-1  transition duration-500 ease select-none hover:bg-indigo-600 focus:outline-none focus:shadow-outline">
                                        Edit
                                        </button> 
                                    </div>
                                    <div class="m-1">
                                        <button type="button" @click="destroyPost(post.id)" class="border border-red-500 bg-red-500 text-xs text-white shadow rounded-md px-1 py-1 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline">
                                        Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="flex flex-col justify-between flex-1"><div class="bg-white text-center text-2xl text-bold rounded p-2"><p>No Posts Found</p></div></div>
                        </template>
                        <pagination class="flex flex-nowrap items-center mt-6" :links="posts.links" :poststotal="posts.total" :postsfrom="((posts.current_page - 1) * posts.per_page) + 1" :poststo="posts.to"/>
                    </div>
                </div>
            </div>
        </div>
        <!-- Create/Edit Post Modal -->
        <jet-dialog-modal :show="show">
            <template #title>
                {{ form.formtitle }} Post
            </template>

            <template #content>
                <form @submit.prevent="submitForm()">
                    <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                                Title
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                type="text" :placeholder="form.titleplaceholder" v-model="form.title" required>
                                <span class="text-red-400" v-if="form.errors.title">{{ form.errors.title }}</span>
                        </div>
                        <div class="mb-4">

                            <label class="block text-gray-700 text-sm font-bold mb-2" for="sub_text">
                                Content
                            </label>
                            <textarea class="bshadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                             type="text" rows="10" :placeholder="form.commentplaceholder"  v-model="form.comment" required></textarea>
                            <span class="text-red-400" v-if="form.errors.comment">{{ form.errors.comment }}</span>
                        </div>
                        <button type="submit" :disabled="form.processing" class="border border-green-500 bg-green-500 text-xs text-white rounded-md px-1 py-1 m-1 transition duration-500 ease select-none hover:bg-green-800 focus:outline-none focus:shadow-outline">
                        {{ form.formtitle }}
                    </button>
                    <button @click="closeModal()" type="button" class="border border-indigo-500 bg-indigo-500 text-xs text-white rounded-md px-1 py-1  transition duration-500 ease select-none hover:bg-indigo-600 focus:outline-none focus:shadow-outline">
                        Cancel
                    </button>
                </form>
            </template>

            <template #footer>
            </template>
        </jet-dialog-modal>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Pagination from '@/Shared/Pagination'
    import FlashMessages from '@/Shared/FlashMessages'
    import { HalfCircleSpinner } from 'epic-spinners'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import { useForm } from '@inertiajs/inertia-vue3'
    import lodash from 'lodash'

    export default {
        setup() {
            const form = useForm({
                title: null,
                titleplaceholder: "Your Post Title",
                comment: null,
                commentplaceholder: "Your Comment....",
                createPost: true,
                id: null,
                formtitle: "Create",
            });
            return { form };
        },
        components: {
            AppLayout,
            Pagination,
            FlashMessages,
            HalfCircleSpinner,
            JetDialogModal,
        },
        props: {
            user: Object,
            posts: Object,
            filters: Object,
            url: String,
        },
        methods: {
            strippedBody(body){
                if(body.length > 250) {
                    return body.slice(0,250) + '...';
                }
                return body;
            },
            destroyPost(id) {
                if(confirm('Are you sure you want to delete this post?')) {
                    this.$inertia.get(route('post.delete', id), {}, {
                        preserveScroll: true,
                        preserveState: true,
                    })
                }
            },
            pullRss() {
                this.loading = true;
                this.$inertia.get(route('posts.rss'), {}, {
                    onSuccess: () => this.loading = false,
                });
            },
            showModal() {
                this.show = true;
                this.showFlashMsg = false;
            },
            showEditModal(id,title,comment) {
                // axios.get('/api/post/'+id).then(response => {
                //     this.form.title = response.data.title;
                //     this.form.comment = response.data.body;
                //     console.log(response.data.title);
                //     console.log(response.data.body);
                // }).catch(error => {
                //     console.log(error);
                // });
                this.show = true;
                this.form.id = id;
                this.form.title = title;
                this.form.comment = comment;
                this.form.createPost = false;
                this.form.formtitle = "Update";
                this.showFlashMsg = false;

            },
            closeModal() {
                this.form.reset();
                this.form.clearErrors();
                this.show = false;
                this.showFlashMsg = true;
            },
            submitForm() {
                if(this.form.createPost) {
                    this.form.post(route('posts.store'), {
                        preserveScroll: false,
                        onSuccess: () => this.closeModal(),
                    });
                }
                else {
                    this.form.post(route('post.update', this.form.id), {
                        preserveScroll: true,
                        preserveState: true,
                        onSuccess: () => this.closeModal(),
                    });
                }
            },
            focusInput() {
                if(this.search != null || this.getString['focus']) {
                    this.$refs.searchInput.focus();
                    this.searchFocus = false;
                }
            },
        },
        data() {
            if(this.filters.search != null) {
                    this.filters.search = decodeURIComponent(this.filters.search);
            }
            return {
                loading: false,
                show: false,
                showFlashMsg: true,
                getString: Array,
                search: this.filters.search,
            }
        },
        watch: {
            search: {
                handler: lodash.throttle(function() {
                    this.getString['search'] = encodeURIComponent(this.search);
                    this.getString['focus'] = true;
                    this.$inertia.get(this.route('posts.index', this.getString));
                }, 150),
                deep: true,
            }
        },
        mounted() {
            this.focusInput();
        },
    }
</script>
