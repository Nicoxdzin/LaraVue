<script setup>
import { watch, onMounted } from "vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import axios from "axios";
import Swal from "sweetalert2";

const props = defineProps({
    mode: String,
    address: Object,
});

const emit = defineEmits(["createdOrUpdated", "close"]);

const form = useForm({
    cep: "",
    state_initials: "",
    city: "",
    neighborhood: "",
    street: "",
    number: "",
    complement: "",
    notes: "",
});

watch(
    () => form.cep,
    (cep) => {
        let cleanedCep = cep.replace(/\D/g, "");

        if (cleanedCep.length === 8) {
            form.cep = `${cleanedCep.substring(0, 5)}-${cleanedCep.substring(
                5
            )}`;
        }

        if (cep.length === 9) {
            form.processing = true;

            axios
                .get(route("api.cep", { cep: cep }))
                .then((response) => {
                    form.processing = false;

                    setFieldsByCepResponse(response.data.data);
                })
                .catch((error) => {
                    form.processing = false;
                    console.error(error);
                    form.errors.cep = "O CEP digitado é inválido";
                });
        }
    }
);

const setFieldsByCepResponse = (response) => {
    form.errors.cep = undefined;

    form.state_initials = response.state_initials;
    form.city = response.city;
    form.neighborhood = response.neighborhood;
    form.street = response.street;

    document.getElementById("number").focus();
};

const submit = (e) => {
    e.preventDefault();

    if (props.mode === "create") {
        create();
    } else {
        update();
    }
};

const create = () => {
    axios
        .post(route("api.addresses.store"), form.data())
        .then((response) => {
            form.reset();

            Swal.fire({
                title: "Endereço cadastrado com sucesso!",
                icon: "success",
                showCancelButton: false,
                confirmButtonText: "Ok",
            });

            emit("createdOrUpdated");
        })
        .catch((error) => {
            handleInvalidFieldsRequest(error);
        });
};

const update = () => {
    axios
        .patch(
            route("api.addresses.update", { address: props.address.id }),
            form.data()
        )
        .then((response) => {
            form.reset();

            Swal.fire({
                title: "Endereço atualizado com sucesso!",
                icon: "success",
                showCancelButton: false,
                confirmButtonText: "Ok",
            });

            emit("createdOrUpdated");
        })
        .catch((error) => {
            handleInvalidFieldsRequest(error);
        });
};

const handleInvalidFieldsRequest = (error) => {
    if (error.response.status === 422) {
        Object.keys(error.response.data.errors).map(
            (key) => (form.errors[key] = error.response.data.errors[key][0])
        );
    }
};

onMounted(() => {
    if (props.mode === "edit") {
        form.cep = props.address.cep;
        form.state_initials = props.address.state_initials;
        form.city = props.address.city;
        form.neighborhood = props.address.neighborhood;
        form.street = props.address.street;
        form.number = props.address.number;
        form.complement = props.address.complement;
        form.notes = props.address.notes;
    }
});
</script>

<template>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-2 grid-rows-4 gap-4">
            <div>
                <div class="p-1">
                    <InputLabel for="cep" value="CEP" />

                    <TextInput
                        id="cep"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="off"
                        placeholder="Digite seu CEP"
                        v-model="form.cep"
                        :disabled="form.processing"
                        :is-invalid="form.errors.cep !== undefined"
                    />

                    <span
                        v-if="form.processing"
                        class="text-sm text-gray-500 dark:text-gray-200"
                    >
                        Buscando CEP...
                    </span>

                    <InputError class="mt-2" :message="form.errors.cep" />
                </div>
            </div>
            <div class="col-start-1 row-start-2">
                <div class="p-1">
                    <InputLabel for="state_initials" value="UF" />

                    <TextInput
                        id="state_initials"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="off"
                        v-model="form.state_initials"
                        :is-invalid="form.errors.state_initials !== undefined"
                        disabled
                    />
                </div>
            </div>
            <div class="col-start-1 row-start-3">
                <div class="p-1">
                    <InputLabel for="city" value="Cidade" />

                    <TextInput
                        id="city"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="off"
                        v-model="form.city"
                        :is-invalid="form.errors.city !== undefined"
                        disabled
                    />
                </div>
            </div>
            <div class="col-start-2 row-start-1">
                <div class="p-1">
                    <InputLabel for="street" value="Rua/Logradouro" />

                    <TextInput
                        id="street"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="off"
                        v-model="form.street"
                        :is-invalid="form.errors.street !== undefined"
                        disabled
                    />
                </div>
            </div>
            <div class="col-start-2 row-start-2">
                <div class="p-1">
                    <InputLabel for="neighborhood" value="Bairro" />

                    <TextInput
                        id="neighborhood"
                        type="Text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="off"
                        v-model="form.neighborhood"
                        disabled
                    />
                </div>
            </div>
            <div>
                <div class="p-1">
                    <InputLabel for="number" value="Número" />

                    <TextInput
                        id="number"
                        type="number"
                        class="mt-1 block w-full appearance-none"
                        autofocus
                        autocomplete="off"
                        placeholder="Exemplo: 2049"
                        required
                        :is-invalid="form.errors.number !== undefined"
                        v-model="form.number"
                    />

                    <InputError class="mt-2" :message="form.errors.number" />
                </div>
            </div>
            <div>
                <div class="p-1">
                    <InputLabel for="complement" value="Complemento" />

                    <TextInput
                        id="complement"
                        type="text"
                        class="mt-1 block w-full"
                        autofocus
                        autocomplete="off"
                        placeholder="Exemplo: Apartamento"
                        :is-invalid="form.errors.complement !== undefined"
                        v-model="form.complement"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.complement"
                    />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4 m-3 p-3">
            <SecondaryButton v-on:click="emit('close')">
                Cancelar
            </SecondaryButton>

            <PrimaryButton
                class="ml-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ mode === "create" ? "Cadastrar" : "Atualizar" }}
            </PrimaryButton>
        </div>
    </form>
</template>
