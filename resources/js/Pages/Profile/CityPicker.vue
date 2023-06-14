<script setup>
import { ref, watch } from "vue"
import { mdiMapMarkerOutline, mdiClose } from '@mdi/js';
import SvgIcon from '@jamescoyle/vue-icon';
import { vOnClickOutside } from '@vueuse/components'

const cities = [
    "Tokyo", "Delhi", "Shanghai", "São Paulo",
    "Mumbai", "Beijing",
    "Cairo", "Dhaka", "Mexico City",
    "Osaka",
    "Karachi",
    "Chongqing",
    "Istanbul",
    "Buenos Aires",
    "Kolkata",
    "Lagos",
    "Kinshasa",
    "Manila",
    "Rio de Janeiro",
    "Guangzhou",
    "Lahore",
    "Shenzhen",
    "Bangalore",
    "Moscow",
    "Tianjin",
    "Jakarta",
    "London",
    "Lima",
    "Bangkok",
    "New York City",
    "Ho Chi Minh City",
    "Bogotá",
    "Hong Kong",
    "Baghdad",
    "Chennai",
    "Bangkok",
    "Hyderabad",
    "Bangkok",
    "Santiago",
    "Bengaluru",
    "Johannesburg",
    "Kolkata",
    "Tehran",
    "Kinshasa",
    "Lima",
    "Bangkok",
    "London",
    "Nagoya",
    "Lima",
    "Bangkok",
    "Rio de Janeiro",
    "Kinshasa",
    "Guangzhou",
    "Bangalore",
    "Shijiazhuang",
    "Mumbai",
    "Tehran",
    "Buenos Aires",
    "Shenzhen",
    "Bangkok",
    "Nanjing",
    "Belo Horizonte",
    "Ahmedabad",
    "Nanjing",
    "Baghdad",
    "Chennai",
    "Paris",
    "Marseille",
    "Lyon",
    "Toulouse",
    "Nice",
    "Nantes",
    "Strasbourg",
    "Montpellier",
    "Bordeaux",
    "Lille",
    "Rennes",
    "Reims",
    "Le Havre",
    "Cannes",
    "Toulon",
    "Saint-Étienne",
    "Grenoble",
    "Dijon",
    "Angers",
    "Nîmes",
    "Aix-en-Provence",
    "Saint-Quentin-en-Yvelines",
    "Brest",
    "Le Mans",
    "Amiens",
    "Tours",
    "Limoges",
    "Clermont-Ferrand",
    "Villeurbanne",
    "Besançon",
    "Orléans",
    "Bogotá",
    "Shenyang",
    "Chongqing",
    "Lima",
    "Bengaluru",
    "Lahore",
    "Bangalore",
    "Lima",
    "Kinshasa",
    "Bangkok",
    "London",
    "Bangkok",
    "Ho Chi Minh City",
    "Bangkok",
    "Jakarta",
    "Baghdad",
    "Bangalore",
    "Bogotá",
    "Rio de Janeiro",
    "Bangkok",
    "Bangkok",
    "Bangkok",
    "Kinshasa",
    "Lima",
    "Bangkok",
    "London",
    "Lima",
    "Bengaluru",
    "Shenzhen",
    "Bangkok",
    "Bangkok",
    "Bangkok",
    "Chennai",
    "Bogotá",
    "Shenzhen",
    "Belo Horizonte",
    "Belo Horizonte",
    "Lima",
    "Kinshasa",
    "Kinshasa",
    "Bengaluru",
    "Los Angeles",
    "Bangkok",
    "Bangalore",
    "London",
    "Ho Chi Minh City",
    "Baghdad",
    "Chennai",
    "Bangalore",
    "Bogotá",
    "Lima",
    "Kinshasa",
    "Bangkok",
    "Bangkok",
    "Bangkok",
    "London",
    "Lima",
    "Bengaluru",
    "Shenzhen",
    "Bangkok",
    "Bangkok",
    "Bangkok",
    "Chennai",
    "Bogotá"
]

const emit = defineEmits(['updateCity'])
defineProps({
    city: String
});


const currentCity = ref('');
const selected = ref(false);
const showCities = ref(false);
const filteredCities = ref(cities);


watch(selected, (newValue, oldValue) => {
    if (newValue)
        emit("updateCity", currentCity.value);
})

watch(currentCity, (newCity, oldCity) => {
    if (selected.value)
        return;
    if (newCity.length == 0) {
        selected.value = false;
        return;
    }
    showCities.value = true;
    filteredCities.value = cities.filter(c => c.includes(newCity));
})

const checkCity = () => {
    if (selected.value == false)
        currentCity.value = "";
    showCities.value = false;
}

const validateCity = (selectedCity) => {
    currentCity.value = selectedCity;
    selected.value = true;
    showCities.value = false;
}

const reset = () => {
    currentCity.value = '';
    selected.value = false;
}

</script>

<template>
    <div class="flex justify-between mt-4 relative items-center" v-on-click-outside="checkCity">
        <div class="w-[85%]">
            <input type="text" :class="{
                    'bg-transparent w-full border-none focus:border-transparent p-0 text-xl focus:ring-0': true,
                    'hover:cursor-not-allowed': selected
                }" placeholder="Ajouter un lieu" v-model="currentCity" :disabled="selected">
            <div v-if="showCities && filteredCities.length > 0"
                class="w-full mt-3 max-h-[200px] bg-black rounded-md p-5 flex flex-col gap-4 overflow-auto absolute">
                <div v-for="(city, index) in filteredCities" :key="index">
                    <div class="hover:cursor-pointer" @click="validateCity(city)">
                        {{ city }}
                    </div>
                </div>
            </div>
        </div>
        <svg-icon v-if="!selected && !showCities" type="mdi" size="24" :path="mdiMapMarkerOutline" />
        <svg-icon v-else class="hover:cursor-pointer" type="mdi" size="24" :path="mdiClose" @click="reset" />

    </div>
</template>