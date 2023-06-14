<script setup>
defineProps({
    post: Object
})
</script>

<template>
    <div class="min-w-[468px]">
        <div class="flex justify-between w-[468px] items-center gap-3 mb-3">
            <div class="flex gap-3 mb-3">
                <div>
                    <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                </div>
                <div>
                    <p>{{ post.user.name }}</p>
                    <p>{{ post.location }}</p>
                    <p>{{ post.description }}</p>
                </div>
            </div>
            <unicon name="ellipsis-h" fill="white"></unicon>
        </div>
        <img class="max-w-[468px] max-h-[650px]" :src="'http://127.0.0.1:8000' + post.image" />
        <div class="mt-3 mb-3 flex flex-row justify-between">
            <div class="flex gap-3">
                <unicon class="w-7 h-7" name="heart" fill="white"></unicon>
                <unicon class="w-7 h-7" name="comment" fill="white"></unicon>
                <unicon class="w-7 h-7" name="telegram-alt" fill="white"></unicon>
            </div>
            <div>
                <unicon class="w-7 h-7" name="bookmark" fill="white"></unicon>
            </div>
        </div>
        <div class="leading-8">
            <div v-if="post.enable_likes">
                334,877 J'aime
            </div>
            <div>
                (lien vers profil)muftimenkofficial Morning Qur'aan Recitation
            </div>
            <div v-if="post.enable_comments">
                <p>Affichier les x commentaires</p>
                <p>2 commentaire afficher ici</p>
            </div>
        </div>
    </div>
</template>