<template>
    <Head title="Gerenciador de Endereços" />

    <div class="bg-slate-800 min-h-screen max-h-screen overscroll-none">
        <div class="flex justify-center pb-3">
            <div
                class="sm:w-full md:w-3/6 p-4 bg-slate-200 border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700 view overflow-y-scroll m-5"
            >
                <div class="flex justify-end">
                    <PrimaryButton
                        class=""
                        :class="{ 'opacity-25': loading }"
                        :disabled="loading"
                        v-on:click="openForm = true"
                    >
                        <p>Novo +</p>
                    </PrimaryButton>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <TextInput
                        id="endereco"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="off"
                        placeholder="ex Rua Exemplo, 1243 ou 83060-420"
                        v-model="searchTerm"
                    />
                    <PrimaryButton
                        class="h-full ml-2"
                        :class="{ 'opacity-25': loading }"
                        :disabled="loading"
                        v-on:click="getAddresses(searchTerm)"
                    >
                        <p>Buscar</p>
                    </PrimaryButton>
                </div>
                <div class="flow-root" v-if="!loading">
                    <ul
                        role="list"
                        class="divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <li
                            v-for="(address, index) in addresses"
                            :key="index"
                            class="py-3 sm:py-4"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-medium text-gray-900 truncate dark:text-white"
                                    >
                                        UF: {{ address.state_initials }}
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900 truncate dark:text-white"
                                    >
                                        Cidade: {{ address.city }}
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900 truncate dark:text-white"
                                    >
                                        Bairro: {{ address.neighborhood }}
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900 truncate dark:text-white"
                                    >
                                        Logradouro: {{ address.street }}
                                    </p>

                                    <p
                                        class="text-sm font-medium text-gray-900 truncate dark:text-white"
                                    >
                                        Número: {{ address.number }}
                                    </p>

                                    <p
                                        class="text-sm font-medium text-gray-900 truncate dark:text-white"
                                        v-if="address.complement"
                                    >
                                        Complemento: {{ address.complement }}
                                    </p>
                                </div>
                                <div class="flex-col text-right">
                                    <div
                                        class="items-center text-base font-semibold text-gray-900 dark:text-white"
                                    >
                                        CEP:
                                        {{ address.cep }}
                                    </div>
                                    <div class="p-1 ml-2">
                                        <PrimaryButton
                                            class="h-full ml-2"
                                            :class="{
                                                'opacity-25': loading,
                                            }"
                                            :disabled="loading"
                                            v-on:click="
                                                () => {
                                                    formMode = 'edit';
                                                    selectedAddress = address;
                                                    openForm = true;
                                                }
                                            "
                                        >
                                            <p>Editar</p>
                                        </PrimaryButton>
                                    </div>
                                    <div class="p-1">
                                        <PrimaryButton
                                            class="h-full ml-2"
                                            :class="{
                                                'opacity-25': loading,
                                            }"
                                            :disabled="loading"
                                            v-on:click="
                                                openDeleteModalAction(address)
                                            "
                                        >
                                            <p>Excluir</p>
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <div class="flex justify-center">
                        <p class="font-semibold text-2xl dark:text-gray-200">
                            Carregando endereços...
                        </p>
                    </div>
                </div>
                <div v-if="addresses.length === 0">
                    <div class="flex justify-center">
                        <p class="font-semibold text-2xl dark:text-gray-200">
                            Nenhum endereço encontrado...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Modal :show="openForm" @close="onCloseModal">
        <Form
            :address="selectedAddress"
            :mode="formMode"
            @close="onCloseModal"
            @createdOrUpdated="handleAddressCreatedOrUpdated"
        />
    </Modal>
    <Modal :show="openDeleteModal" @close="handleCloseDeleteModal">
        <Delete
            :address="selectedAddress"
            @close="handleCloseDeleteModal"
            @deleted="handleDeletedAddress"
        />
    </Modal>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head } from "@inertiajs/vue3";
import Form from "./Components/Form.vue";
import axios from "axios";
import Delete from "./Components/Delete.vue";

const searchTerm = ref("");

const loading = ref(false);

const addresses = ref([]);

const openForm = ref(false);

const formMode = ref("create");

const openDeleteModal = ref(false);

const selectedAddress = ref(null);

const onCloseModal = () => {
    openForm.value = false;
    formMode.value = "create";
    selectedAddress.value = null;
};

const handleAddressCreatedOrUpdated = async () => {
    await getAddresses();

    onCloseModal();
};

const handleCloseDeleteModal = () => {
    selectedAddress.value = null;
    openDeleteModal.value = false;
};

const handleDeletedAddress = () => {
    handleCloseDeleteModal();

    getAddresses();
};

const openDeleteModalAction = async (address) => {
    selectedAddress.value = address;
    openDeleteModal.value = true;
};

const getAddresses = async (query) => {
    loading.value = true;

    if (query === "") {
        query = undefined;
    }

    axios
        .get(route("api.addresses.index"), {
            params: {
                query: query,
            },
        })
        .then((response) => {
            addresses.value = response.data.data;
            loading.value = false;
        })
        .catch((error) => {
            console.error(error);
        });
};

onMounted(() => {
    getAddresses();
});
</script>

<style>
.view {
    min-height: 90vh;
    max-height: 90vh;
}
</style>
