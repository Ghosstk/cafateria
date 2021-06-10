<template>
    <div v-if="Object.keys(errors).length" class="rounded-md bg-red-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <font-awesome-icon :icon="['fas','exclamation-triangle']"/>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    Volt {{ Object.keys(errors).length }} hiba a beküldött adatok között.
                </h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li v-for="error in errors">
                            {{error}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        errors: Object
    }
}
</script>

<style scoped>

</style>
