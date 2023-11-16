<template>
	<div>
		<div>
			<div class="h-[830px] flex flex-col xl:flex-row">
				<div>
					<div v-if='image' class="sm:h-[808px] aspect-square">
						<img ref='img' crossOrigin="Anonymous" id='image' :src="image" class='min-w-full min-h-full'
							:style='filters'>
					</div>
					<div>
					</div>
				</div>
				<div v-if='image'>
					<div class="ml-6">
						<div class="card-header py-1">
							<h5 class="card-title m-0 text-center text-2xl"><small>Presets</small></h5>
						</div>
						<div class="flex flex-wrap gap-4" v-show='image'>
							<div class="card text-white  min-w-0 w-[110px] grow sm:grow-0 sm:h-[102px] mb-2 text-center " v-for='preset in presets()'
								@click='selectAndLoadPreset(preset)'
								:class="[(selectedPreset && (selectedPreset.name == preset.name)) ? 'bg-primary' : 'bg-secondary']"
								:key="preset.index">
								<div class="text-center shrink max-w-[100px] min-w-0">
									<img crossOrigin="Anonymous" :src="image"
										class='rounded sm:w-[100px] aspect-square object-fill'
										:style='makeFilter(preset.filterSet)'>
										<div class=""><small>{{ preset.name }}</small></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import DefaultImage from '@/../images/default.jpg'
import Presets from '../Components/Presets.vue'


export default {
	name: 'ImageFilterApp',
	components: {
		Presets
	},
	props: {
		url: {
            type: String,
            default: DefaultImage
        }
	},
	data() {
		return {
			image: null,
			filterFunctions: null,
			width: 0,
			height: 0,
			selectedPreset: null,
			textCopied: false
		}
	},
	created() {
		this.image = this.url;
		this.filterFunctions = this.defaultValues();
	},
	watch: {
		textCopied() {
			setTimeout(function () {
				if (this.textCopied == true) {
					this.textCopied = false;
				}
			}.bind(this), 350);
		}
	},
	computed: {
		filters() {
			return this.makeFilter();
		}
	},
	methods: {
		makeFilter(filterSet) {

			if (!filterSet) {
				filterSet = this.filterFunctions;
			}

			var filterString = "";
			var defaultValues = this.defaultValues();
			for (var filterFunc in filterSet) {
				if (filterSet[filterFunc] !== defaultValues[filterFunc]) {
					if (filterFunc == 'hueRotate') {
						filterString = filterString + "hue-rotate(" + filterSet[filterFunc] + "deg) ";
					}
					else if (filterFunc == 'blur') {
						filterString = filterString + filterFunc + "(" + filterSet[filterFunc] + "px) ";
					}
					else {
						filterString = filterString + filterFunc + "(" + filterSet[filterFunc] + ") ";
					}
				}
			}

			return { filter: filterString };
		},
		setToDefault() {
			this.filterFunctions = this.defaultValues();
		},
		defaultValues() {
			return {
				grayscale: 0,
				sepia: 0,
				saturate: 1,
				hueRotate: 0,
				invert: 0,
				brightness: 1,
				contrast: 1,
				blur: 0,
				opacity: 1
			}
		},
		changeImage(e) {
			var files = e.target.files || e.dataTransfer.files;
			if (!files.length)
				return;

			this.loadImage(files[0]);
		},
		loadImage(file) {
			var reader = new FileReader();
			var image = new Image();

			reader.onload = (e) => {
				this.image = e.target.result;
				image.src = e.target.result;
			};
			reader.readAsDataURL(file);
			image.onload = function () {
				document.getElementById("image").setAttribute("data-original-width", this.width);
				document.getElementById("image").setAttribute("data-original-height", this.height);
			}
		},
		presets() {
			return {
				brannes: { name: 'Brannes', filterSet: { sepia: 0.5, contrast: 1.4 } },
				inkwell: { name: 'Inkwell', filterSet: { sepia: 0.3, contrast: 1.1, brightness: 1.1, grayscale: 1 } },
				lofi: { name: 'Lo-Fi', filterSet: { saturate: 1.1, contrast: 1.5 } },
				moon: { name: 'Moon', filterSet: { grayscale: 1, contrast: 1.1, brightness: 1.1 } },
				nashville: { name: 'Nashville', filterSet: { sepia: 0.2, contrast: 1.2, brightness: 1.05, saturate: 1.2 } },
				toaster: { name: 'Toaster', filterSet: { contrast: 1.5, brightness: 0.9 } },
				walden: { name: 'Walden', filterSet: { brightness: 1.1, hueRotate: '-10', sepia: .3, saturate: 1.6 } },
				willow: { name: 'Willow', filterSet: { grayscale: 0.5, contrast: 0.95, brightness: 0.9 } },
				xpro2: { name: 'X-pro II', filterSet: { sepia: 0.3 } },
			}
		},
		selectAndLoadPreset(preset) {
			if (preset) {
				this.filterFunctions = preset.filterSet;
				this.selectedPreset = preset;
			}
		},
		download() {
			const canvas = document.createElement('canvas');
			canvas.width = document.getElementById("image").getAttribute("data-original-width");
			canvas.height = document.getElementById("image").getAttribute("data-original-height");

			const ctx = canvas.getContext('2d');
			ctx.filter = this.filters.filter;

			var img = new Image();
			img.src = this.image;
			img.onload = function () {
				ctx.drawImage(this, 0, 0, canvas.width, canvas.height);
				const anchor = document.createElement('a');
				anchor.href = canvas.toDataURL();
				anchor.download = 'filtered.png';
				anchor.click();
			};
		},
		defaultImage() {
			return DefaultImage;
		}
	}
}

</script>
