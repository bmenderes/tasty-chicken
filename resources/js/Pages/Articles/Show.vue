<template>
  <app-layout title="article.title">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ article.title }}
        </h2>

        <div>
          <jet-secondary-button
            @click.prevent="editing = !editing"
            type="button"
            v-if="permissions.update"
          >
            Editing
          </jet-secondary-button>
          <jet-danger-button
            @click.prevent="deleting = true"
            type="button"
            class="ml-3"
            v-if="permissions.delete"
          >
            Delete
          </jet-danger-button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div v-if="!editing" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            {{ article.body }}
          </div>
        </div>
      </div>

      <!-- Edit Form -->
      <jet-form-section v-else @submitted="submitForm">
        <template #title> Update Article </template>

        <template #description>
          Enter the details of the article you want to create.
        </template>

        <template #form>
          <!-- Title -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="title" value="Title" />
            <jet-input
              id="title"
              type="text"
              class="mt-1 block w-full"
              v-model="form.title"
              autocomplete="title"
            />
            <jet-input-error :message="form.errors.title" class="mt-2" />
          </div>

          <!-- Body -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="body" value="Body" />

            <textarea
              class="
                border-gray-300
                focus:border-indigo-300
                focus:ring
                focus:ring-indigo-200
                focus:ring-opacity-50
                rounded-md
                shadow-sm
                mt-1
                block
                w-full
              "
              key=""
              id="body"
              v-model="form.body"
            ></textarea>
            <jet-input-error :message="form.errors.body" class="mt-2" />
          </div>
        </template>

        <template #actions>
          <jet-action-message :on="form.recentlySuccessful" class="mr-3">
            Edited.
          </jet-action-message>

          <jet-button
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Update
          </jet-button>
        </template>
      </jet-form-section>
      <!-- Delete Account Confirmation Modal -->
      <jet-dialog-modal :show="deleting" @close="deleting = false">
        <template #title> Delete Article </template>

        <template #content>
          Are you sure you want to delete your article?
        </template>

        <template #footer>
          <jet-secondary-button @click="deleting = false">
            Cancel
          </jet-secondary-button>

          <jet-danger-button
            class="ml-3"
            @click="deleteArticle"
            :class="{ 'opacity-25': deleteForm.processing }"
            :disabled="deleteForm.processing"
          >
            Delete Article
          </jet-danger-button>
        </template>
      </jet-dialog-modal>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";

export default defineComponent({
  props: ["article", "permissions"],
  components: {
    AppLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    JetDangerButton,
    JetDialogModal,
  },
  data() {
    return {
      editing: false,
      deleting: false,
      form: this.$inertia.form({
        title: this.article.title,
        body: this.article.body,
      }),
      deleteForm: this.$inertia.form({}),
    };
  },
  methods: {
    submitForm() {
      this.form.put(this.route("articles.update", this.article), {
        errorBag: "UpdateArticle",
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.editing = false;
        },
      });
    },
    deleteArticle() {
      this.deleteForm.delete(this.route("articles.destroy", this.article));
    },
  },
});
</script>