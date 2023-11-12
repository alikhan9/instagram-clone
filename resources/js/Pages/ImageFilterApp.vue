<template>
	<div>
		<div>
			<div
				class="border-b min-w-full flex justify-between border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold px-5">
				<svg-icon class="hover:cursor-pointer" type="mdi" size="26" :path="mdiArrowLeft" />
				<p>Rogner</p>
				<button class="text-[hsl(204,90%,49%)] text-base hover:text-white" @click="step++">Suivant</button>
			</div>
			<div class="h-[830px] flex">
				<div>
					<div v-if="!image" class='form-group pt-3'>
						<input type="file" @change="changeImage" class='form-control'>
					</div>
					<div v-else class='form-group m-0 clearfix'>
						<button @click="image = null" class='btn btn-primary btn-sm'><i class='fa fa-image fa-fw'></i>
							New image</button>
					</div>
		
					<div v-if='image' class="h-[808px] aspect-square">
						<img ref='img' crossOrigin="Anonymous" id='image' :src="image" class='min-w-full min-h-full'
							:style='filters'>
					</div>
					<div>


					</div>
				</div>
				<div v-if='image'>
					<div class="ml-2">
						<div class="card-header py-1">
							<h5 class="card-title m-0 text-center text-2xl"><small>Presets</small></h5>
						</div>
						<div class="grid grid-cols-3 gap-4" v-show='image'>
							<div class="card text-white w-[110px] h-[102px] mb-2 text-center" v-for='preset in presets()'
								@click='selectAndLoadPreset(preset)'
								:class="[(selectedPreset && (selectedPreset.name == preset.name)) ? 'bg-primary' : 'bg-secondary']"
								:key="preset.index">
								<div class="text-center">
									<img crossOrigin="Anonymous" :src="image"
										class='rounded w-[100px] aspect-square object-fill'
										:style='makeFilter(preset.filterSet)'>
								</div>
								<div class=""><small>{{ preset.name }}</small></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="col-md-5 sticky-top"> -->
		<!-- <div class="card">
							<div class="card-header clearfix">
								<h5 class="card-title float-left m-0">Adjust the filter</h5>
								<button type='button' class='btn btn-danger btn-sm float-right' @click='setToDefault()'
									:disabled='!image'><i class='fa fa-undo fa-fw'></i> Reset</button>
							</div>
							<div class="card-body row">
								<div class="col-md-6" :data-filter-disabled='!image'>
									<div class="form-group p-0">
										<label>Grayscale ({{ filterFunctions.grayscale }})</label>
										<input type="range" class="form-control" step='0.1' min="0" max="1"
											v-model='filterFunctions.grayscale' :disabled='!image'>
									</div>
									<div class="form-group p-0">
										<label>Blur ({{ filterFunctions.blur }}px)</label>
										<input type="range" class="form-control" step='1' min="0" max="50"
											v-model='filterFunctions.blur' :disabled='!image'>
									</div>
									<div class="form-group p-0">
										<label>Sepia ({{ filterFunctions.sepia }})</label>
										<input type="range" class="form-control" step='0.1' min="0" max="1"
											v-model='filterFunctions.sepia' :disabled='!image'>
									</div>
									<div class="form-group p-0">
										<label>Saturate ({{ filterFunctions.saturate }})</label>
										<input type="range" class="form-control" step='0.1' min="0" max="2"
											v-model='filterFunctions.saturate' :disabled='!image'>
									</div>
									<div class="form-group p-0">
										<label>Opacity ({{ filterFunctions.opacity }})</label>
										<input type="range" class="form-control" step='0.1' min="0" max="1"
											v-model='filterFunctions.opacity' :disabled='!image'>
									</div>
								</div>
								<div class="col-md-6" :data-filter-disabled='!image'>
									<div class="form-group p-0">
										<label>Brightness ({{ filterFunctions.brightness }})</label>
										<input type="range" class="form-control" step='0.01' min="0" max="5"
											v-model='filterFunctions.brightness' :disabled='!image'>
									</div>
									<div class="form-group p-0">
										<label>Contrast ({{ filterFunctions.contrast }})</label>
										<input type="range" class="form-control" step='0.01' min="0" max="10"
											v-model='filterFunctions.contrast' :disabled='!image'>
									</div>
									<div class="form-group p-0">
										<label>Hue-rotate ({{ filterFunctions.hueRotate }}deg)</label>
										<input type="range" class="form-control" step='1' min="-360" max="360"
											v-model='filterFunctions.hueRotate' :disabled='!image'>
									</div>
									<div class="form-group p-0">
										<label>Invert ({{ filterFunctions.invert }})</label>
										<input type="range" class="form-control" step='0.1' min="0" max="2"
											v-model='filterFunctions.invert' :disabled='!image'>
									</div>
								</div>
							</div>
						</div> -->
		<!-- </div> -->
	</div>
</template>

<script>
import DefaultImage from '@/../images/default.jpg'
import Presets from '../Components/Presets.vue'
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiArrowLeft } from '@mdi/js';


export default {
	name: 'ImageFilterApp',
	components: {
		Presets
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
		this.image = this.defaultImage()
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

<style>
pre {
	color: rgb(76, 186, 135);
	font-size: 18px;
	font-weight: bolder;
}

input[type=range] {
	padding: 0;
	border: none;
}

[data-filter-disabled=true],
button[disabled=true],
button[disabled=disabled],
[data-filter-disabled=true] input {
	background: #f1f1f1;
	cursor: not-allowed;
}

#copy-code {
	position: relative;
}

#copy-code .btn {
	position: absolute;
	top: 0;
	right: 0;
}

.owl-nav .owl-next {
	right: 0
}

.owl-nav .owl-prev {
	left: 0;
}

.owl-carousel .owl-item img {
	width: auto !important;
	margin: auto;
	height: 100px;
}

.owl-nav>.disabled {
	color: #aaa;
}
</style>
