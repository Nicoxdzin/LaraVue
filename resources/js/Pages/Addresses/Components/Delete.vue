<script setup>
import { ref } from "vue";
import Swal from "sweetalert2";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const loading = ref(false);

const props = defineProps({
    address: Object,
});

const emit = defineEmits(["close", "deleted"]);

const deleteAddress = async () => {
    loading.value = true;

    axios
        .delete(
            route("api.addresses.delete", {
                address: props.address.id,
            })
        )
        .then((response) => {
            loading.value = false;

            Swal.fire({
                icon: "success",
                title: "Sucesso!",
                text: "Endereço excluído com sucesso!",
            });

            emit("deleted");
        })
        .catch((error) => {
            console.error(error);
        });
};
</script>

<template>
    <div class="flex justify-center">
        <p class="font-semibold text-2xl dark:text-gray-200">
            Deseja realmente excluir este endereço?
        </p>
    </div>
    <div class="flex items-center justify-center mt-4 m-3 p-3">
        <PrimaryButton
            class="ml-4"
            :class="{ 'opacity-25': loading }"
            :disabled="loading"
            v-on:click="emit('close')"
        >
            Cancelar
        </PrimaryButton>
        <PrimaryButton
            class="ml-4"
            :class="{ 'opacity-25': loading }"
            :disabled="loading"
            v-on:click="deleteAddress"
        >
            Sim, desejo excluir.
        </PrimaryButton>
    </div>
</template>
