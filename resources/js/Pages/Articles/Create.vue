<template>
  <app-layout title="Create a New Article">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create a New Article
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <form @submit.prevent="submitForm">
            <input
              name="title"
              type="text"
              placeholder="title"
              v-model="form.title"
            />
            <br />
            <textarea
              name="body"
              placeholder="Body"
              v-model="form.body"
            ></textarea>
            <br />
            <button type="submit">Create Article</button>
          </form>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

export default defineComponent({
  components: {
    AppLayout,
  },
  data() {
    return {
      form: this.$inertia.form({
        title: "",
        body: "",
      }),
    };
  },
  methods: {
    submitForm() {
      this.form.post(this.route("articles.store"));
    },
  },
});
</script>
