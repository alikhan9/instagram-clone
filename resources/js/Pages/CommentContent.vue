<script setup>
import { ref, onMounted } from "vue";
import SingleComment from "./Components/Comments/SingleComment.vue";

const emit = defineEmits(["sendResponseComment"]);
const props = defineProps({
    comment: Object,
});

const showResponses = ref(false);

const sendName = (data) => {
    emit("sendResponseComment", data);
};
</script>

<template>
    <div>
        <SingleComment @sendResponseComment="sendName" :comment="comment">
            <div class="text-sm text-[hsl(0,0%,60%)] mt-5" v-if="comment.responses.length > 0">
                <div v-if="!showResponses" @click="showResponses = !showResponses" class="hover:cursor-pointer">
                    <span class="tracking-tighter mr-5">——</span> Afficher les réponses ({{
                        comment.responses.length
                    }})
                </div>
                <div v-else>
                    <div class="hover:cursor-pointer" @click="showResponses = !showResponses">
                        <span class="tracking-tighter mr-5">——</span> Masquer les réponses
                    </div>
                    <div class="mt-5" v-for="(response, index) in comment.responses" :key="index">
                        <SingleComment @sendResponseComment="sendName" :comment="response" />
                    </div>
                </div>
            </div>
        </SingleComment>
    </div>
</template>
