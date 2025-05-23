<script setup lang="ts">
import { FormCheck, FormInput, FormLabel } from "@/components/Base/Form";
import Button from "@/components/Base/Button";

import { Dialog } from "@/components/Base/Headless";
import _ from "lodash";
import router from "@/router";
import { useVuelidate } from '@vuelidate/core'
import { 
  required,
  email,
  helpers,
  minLength,
  maxLength,
  requiredIf, } from '@vuelidate/validators'
import { reactive, computed, ref } from "vue";
import store from "@/stores";

import TermsAndConditions from "@/views/TermsAndConditions.vue";
import PrivacyPolicy from "@/views/PrivacyPolicy.vue";

const loading = ref(false);
const submitted = ref(false);
const currentComponent = ref();
const model = reactive({  
  name: '',
  email: '',
  password: '',
  confirm_password: '',
  terms: false,
});


const rules = computed(() => {
  return {
    name: {
      required: helpers.withMessage(
        "Please enter the your name.",
        required
      ),
    },
    email: {
      required: helpers.withMessage(
        "Please enter your email.",
        required
      ),
      email: helpers.withMessage(
        "Please enter valid email address.",
        email
      ),
    },
    password: {
      required: helpers.withMessage(
        "Please enter the password.",
        required
      ),
    },
    confirm_password: {
        sameAsPassword: helpers.withMessage(
        "Password and confirm password should be same.",
        (value) => value === model.password
    )},
    terms: { 
        required: helpers.withMessage(
            "Please accept the terms and conditions.",
            required
        ),
    },
}
});

const v$ = useVuelidate(rules, model);

async function submit () {
    submitted.value = true;
    v$.value.$validate();
    if (v$.value.$error) {
        return false;
    }
    await store
    .dispatch("notes/save", model)
    .then(() => {
      loading.value = false;
      submitted.value = false;
      successNotification.value?.showToast();

      router.push({ name: "Notes" });
    })
    .catch((err: any) => {
      loading.value = false;
      isErrored.value = true;
      if (err.response) {
        message.value = err.response.data.message;
      }
    });
    return true;
}

const superlargeModalSizePreview = ref(false);
const setSuperlargeModalSizePreview = (value: boolean) => {
  superlargeModalSizePreview.value = value;
};
function showModel(component: any) {
  currentComponent.value = component;
  setSuperlargeModalSizePreview(true);
}
</script>

<template>
  <div
    class="container grid lg:h-screen grid-cols-12 lg:max-w-[1550px] 2xl:max-w-[1750px] py-10 px-5 sm:py-14 sm:px-10 md:px-36 lg:py-0 lg:pl-14 lg:pr-12 xl:px-24"
  >
    <div
      :class="[
        'relative z-50 h-full col-span-12 p-7 sm:p-14 bg-white rounded-2xl lg:bg-transparent lg:pr-10 lg:col-span-5 xl:pr-24 2xl:col-span-4 lg:p-0 dark:bg-darkmode-600',
        'before:content-[\'\'] before:absolute before:inset-0 before:-mb-3.5 before:bg-white/40 before:rounded-2xl before:mx-5 dark:before:hidden',
      ]"
    >
      <div
        class="relative z-10 flex flex-col justify-center w-full h-full py-2 lg:py-32"
      >
        <div
          class="rounded-[0.8rem] w-[55px] h-[55px] border border-primary/30 flex items-center justify-center"
        >
          <div
            class="relative flex items-center justify-center w-[50px] rounded-[0.6rem] h-[50px] bg-gradient-to-b from-theme-1/90 to-theme-2/90 bg-white"
          >
            <div class="w-[26px] h-[26px] relative -rotate-45 [&_div]:bg-white">
              <div
                class="absolute w-[20%] left-0 inset-y-0 my-auto rounded-full opacity-50 h-[75%]"
              ></div>
              <div
                class="absolute w-[20%] inset-0 m-auto h-[120%] rounded-full"
              ></div>
              <div
                class="absolute w-[20%] right-0 inset-y-0 my-auto rounded-full opacity-50 h-[75%]"
              ></div>
            </div>
          </div>
        </div>
        <div class="mt-10">
          <div class="text-2xl font-medium">Sign Up</div>
          <div class="mt-2.5 text-slate-600 dark:text-slate-400">
            Already have an account?
            <RouterLink class="font-medium text-primary" :to="{ name: 'Login' }"> Sign In </RouterLink>
          </div>
          <form @submit.prevent="submit">
            <div class="mt-6">
                <FormLabel>Name*</FormLabel>
                <FormInput
                    type="text"
                    class="block px-4 py-3.5 rounded-[0.6rem] border-slate-300/80"
                    placeholder="Enter your name"
                    v-model="model.name"
                    :class="{
                      'border-danger': submitted && v$.name.$errors.length,
                    }"
                />
                <div :class="{ error: v$.name.$errors.length }">                
                    <div class="text-danger" v-for="error of v$.name.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>

                <FormLabel class="mt-5">Email*</FormLabel>
                <FormInput
                    type="text"
                    class="block px-4 py-3.5 rounded-[0.6rem] border-slate-300/80"
                    placeholder="Enter your Email Address"
                    v-model="model.email"
                    :class="{
                      'border-danger': submitted && v$.email.$errors.length,
                    }"
                />
                <div :class="{ error: v$.email.$errors.length }">                
                    <div class="text-danger" v-for="error of v$.email.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <FormLabel class="mt-5">Password*</FormLabel>
                <FormInput
                type="password"
                class="block px-4 py-3.5 rounded-[0.6rem] border-slate-300/80"
                placeholder="************"
                v-model="model.password"
                :class="{
                    'border-danger': submitted && v$.password.$errors.length,
                }"
                />
                <div :class="{ error: v$.password.$errors.length }">                
                    <div class="text-danger" v-for="error of v$.password.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <!-- <div class="grid w-full h-1.5 grid-cols-12 gap-4 mt-3.5">
                <div
                    class="h-full col-span-3 border rounded active bg-slate-400/30 border-slate-400/20 [&.active]:bg-theme-1/30 [&.active]:border-theme-1/20"
                ></div>
                <div
                    class="h-full col-span-3 border rounded active bg-slate-400/30 border-slate-400/20 [&.active]:bg-theme-1/30 [&.active]:border-theme-1/20"
                ></div>
                <div
                    class="h-full col-span-3 border rounded active bg-slate-400/30 border-slate-400/20 [&.active]:bg-theme-1/30 [&.active]:border-theme-1/20"
                ></div>
                <div
                    class="h-full col-span-3 border rounded bg-slate-400/30 border-slate-400/20 [&.active]:bg-theme-1/30 [&.active]:border-theme-1/20"
                ></div>
                </div> -->
                <!-- <a
                href=""
                class="block mt-3 text-xs text-slate-500/80 sm:text-sm dark:text-slate-400"
                >
                What is a secure password?
                </a> -->
                <FormLabel class="mt-5">Password Confirmation*</FormLabel>
                <FormInput
                    type="password"
                    class="block px-4 py-3.5 rounded-[0.6rem] border-slate-300/80"
                    placeholder="************"
                    v-model="model.confirm_password"
                    :class="{
                      'border-danger': submitted && v$.confirm_password.$errors.length,
                    }"
                />
                <div :class="{ error: v$.confirm_password.$errors.length }">                
                    <div class="text-danger" v-for="error of v$.confirm_password.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <div
                class="flex items-center mt-5 text-xs text-slate-500 sm:text-sm"
                >
                <FormCheck.Input
                    id="privacy-policy"
                    type="checkbox"
                    class="mr-2 border"
                    v-model="model.terms"
                    :class="{
                      'border-danger': submitted && v$.terms.$errors.length,
                    }"
                />
                <div :class="{ error: v$.terms.$errors.length }">                
                    <div class="text-danger" v-for="error of v$.terms.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <label class="cursor-pointer select-none" htmlFor="privacy-policy">
                    I agree to the Planedge
                </label>
                <a class="ml-1 text-primary dark:text-slate-200" href="#"
                    @click="showModel('PrivacyPolicy')"
                    >
                    Privacy Policy
                </a>
                .
                </div>
                <div class="mt-5 text-center xl:mt-8 xl:text-left">
                <Button
                    variant="primary"
                    rounded
                    class="bg-gradient-to-r from-theme-1/70 to-theme-2/70 w-full py-3.5 xl:mr-3 dark:border-darkmode-400"
                    type="submit"
                >
                    Sign Up
                </Button>
                <Button
                    variant="outline-secondary"
                    rounded
                    class="bg-white/70 w-full py-3.5 mt-3 dark:bg-darkmode-400"
                    @click="() => {
                    router.push({ name: 'Login' });}"
                >
                    Sign In
                </Button>
                
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div
    class="fixed container grid w-screen inset-0 h-screen grid-cols-12 lg:max-w-[1550px] 2xl:max-w-[1750px] pl-14 pr-12 xl:px-24"
  >
    <div
      :class="[
        'relative h-screen col-span-12 lg:col-span-5 2xl:col-span-4 z-20',
        'after:bg-white after:hidden after:lg:block after:content-[\'\'] after:absolute after:right-0 after:inset-y-0 after:bg-gradient-to-b after:from-white after:to-slate-100/80 after:w-[800%] after:rounded-[0_1.2rem_1.2rem_0/0_1.7rem_1.7rem_0] dark:after:bg-darkmode-600 dark:after:from-darkmode-600 dark:after:to-darkmode-600',
        'before:content-[\'\'] before:hidden before:lg:block before:absolute before:right-0 before:inset-y-0 before:my-6 before:bg-gradient-to-b before:from-white/10 before:to-slate-50/10 before:bg-white/50 before:w-[800%] before:-mr-4 before:rounded-[0_1.2rem_1.2rem_0/0_1.7rem_1.7rem_0] dark:before:from-darkmode-300 dark:before:to-darkmode-300',
      ]"
    ></div>
    <div
      :class="[
        'h-full col-span-7 2xl:col-span-8 lg:relative',
        'before:content-[\'\'] before:absolute before:lg:-ml-10 before:left-0 before:inset-y-0 before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 before:w-screen before:lg:w-[800%]',
        'after:content-[\'\'] after:absolute after:inset-y-0 after:left-0 after:w-screen after:lg:w-[800%] after:bg-texture-white after:bg-fixed after:bg-center after:lg:bg-[25rem_-25rem] after:bg-no-repeat',
      ]"
    >
      <div
        class="sticky top-0 z-10 flex-col justify-center hidden h-screen ml-16 lg:flex xl:ml-28 2xl:ml-36"
      >
        <div
          class="leading-[1.4] text-[2.6rem] xl:text-5xl font-medium xl:leading-[1.2] text-white"
        >
          Embrace Excellence <br />
          in Project Planning Department
        </div>
        <div class="mt-5 text-base leading-relaxed xl:text-lg text-white/70">
          "Without leaps of imagination or dreaming, we lose the excitement of possibilities. Dreaming, after all, is a form of planning." <br />
          - Gloria Steinem
        </div>
      </div>
    </div>
  </div>
  <Dialog 
        size="xl" 
        :open="superlargeModalSizePreview"
        class="overflow-auto" 
        @close="
        () => {
            setSuperlargeModalSizePreview(false);
        }
        ">
        <Dialog.Panel class="p-10 overflow-auto">
            <TermsAndConditions v-if="currentComponent === 'TermsAndConditions'"></TermsAndConditions>
            <PrivacyPolicy v-if="currentComponent === 'PrivacyPolicy'"></PrivacyPolicy>
        </Dialog.Panel>
    </Dialog>
</template>