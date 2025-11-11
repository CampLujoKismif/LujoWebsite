<template>
  <CamperCard
    :camper="camper"
    :selected="selected"
    :forms-complete="formsComplete"
    :show-selection-actions="false"
    :allow-forms-access="allowFormsAccess"
    :already-registered="alreadyRegistered"
    :disabled="disabled"
    @edit-camper="handleEdit"
    @request-forms="handleRequestForms"
  />
</template>

<script>
import CamperCard from './CamperCard.vue'

export default {
  name: 'ParentPortalCamperCard',
  components: {
    CamperCard,
  },
  props: {
    camper: {
      type: Object,
      required: true,
    },
    selected: {
      type: Boolean,
      default: false,
    },
    formsComplete: {
      type: Boolean,
      default: false,
    },
    allowFormsAccess: {
      type: Boolean,
      default: false,
    },
    alreadyRegistered: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    livewireComponentId: {
      type: String,
      default: null,
    },
    formsAction: {
      type: String,
      default: 'openCamperForms',
    },
  },
  methods: {
    handleEdit(camper) {
      this.callLivewireMethod('openCamperModal', camper?.id)
      this.$emit('edit-camper', camper)
    },
    handleRequestForms(camper) {
      this.callLivewireMethod(this.formsAction, camper?.id)
      this.$emit('request-forms', camper)
    },
    callLivewireMethod(method, camperId) {
      if (!method || !camperId) {
        return
      }

      const livewireComponent = this.findLivewireComponent()

      if (livewireComponent && typeof livewireComponent.call === 'function') {
        livewireComponent.call(method, camperId)
        return
      }

      window.dispatchEvent(
        new CustomEvent('parent-portal:camper-action', {
          detail: { method, camperId },
        }),
      )
    },
    findLivewireComponent() {
      if (this.livewireComponentId && window.Livewire?.find) {
        return window.Livewire.find(this.livewireComponentId)
      }

      if (this.$el) {
        const livewireElement = this.$el.closest('[wire\\:id]')
        if (livewireElement && window.Livewire?.find) {
          return window.Livewire.find(livewireElement.getAttribute('wire:id'))
        }
      }

      return null
    },
  },
}
</script>

