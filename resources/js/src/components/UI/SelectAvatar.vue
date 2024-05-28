<template>
    <div class="select-img" @click="handleClick">
        <div class="select-img__label">
            <input
                type="file"
                name="image"
                class="select-img__input"
                ref="image"
                @change="handleImageChange"
            />
            <figure class="select-img__figure">
                <img
                    :src="selectedImages"
                    alt=""
                    class="select-img__img"
                    :class="selectedImages ? 'select-img__img_active' : ''"
                />
                <figcaption class="select-img__figcaption">
                    <i class="bi bi-image-fill"></i>
                </figcaption>
            </figure>
        </div>
    </div>
</template>

<script>
import {useLaravel} from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
const {image: imageClient} = useLaravel();

export default {
    name: "SelectAvatar",
    props: {
        image: {
            type: String,
        },
    },
    data() {
        return {
            selectedImages: null,
        };
    },
    watch: {
        image(newValue, oldValue) {
            this.selectedImages = newValue;
        },
    },
    methods: {
        handleClick() {
            this.$refs.image.click();
        },
       async handleImageChange(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.selectedImages = e.target.result;
                };
                reader.readAsDataURL(file);

                await imageClient.store(file)
                    .then((res) => {
                        this.$emit("select-image", res.data.image);
                        toast.success('Изображение загружено!')
                    })
                    .catch((err) => {
                        toast.error('Ошибка', err.response.data.message)
                    })

            }
        },
    },
    emits: ["select-image"],
};
</script>

<style scoped lang="scss">
.select-img {
    width: 200px;
    height: 200px;
    cursor: pointer;
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    overflow: hidden;
    border: 1px solid var(--surface-border);

    &__label {
        width: 100%;
        height: 100%;
    }

    &__input {
        display: none;
    }

    &__figure {
        position: relative;
        width: 100%;
        height: 100%;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    &__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 15px;
        display: none;

        &_active {
            display: block;
        }
    }

    &__img_active ~ &__figcaption {
        opacity: 0;
    }

    &__figcaption {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background-color: transparent;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 45px;
        color: var(--surface-border);
    }

    &__figure:hover &__figcaption {
        opacity: 1;
        color: #fff;
        background-color: rgba(0, 0, 0, 0.5);
    }
}
</style>
