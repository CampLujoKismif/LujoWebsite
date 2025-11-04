<template>
    <div class="tinymce-wrapper">
        <Editor
            :key="editorId"
            :id="editorId"
            api-key="nurbhuah0h6z3znbfbmr04uzsvp7jah1ud2zkg4ae3uvji9b"
            :init="editorConfig"
            v-model="htmlContent"
            @update:model-value="onContentUpdate"
        />
        <!-- Hidden input to store HTML content -->
        <input 
            type="hidden" 
            :name="inputName" 
            :id="inputId" 
            :value="htmlContent"
        />
        
        <!-- Preview Modal -->
        <div v-if="showPreview" class="preview-modal-overlay" @click.self="closePreview">
            <div class="preview-modal-content">
                <div class="preview-modal-header">
                    <h3 class="preview-modal-title">Preview</h3>
                    <button @click="closePreview" class="preview-modal-close">&times;</button>
                </div>
                <div class="preview-modal-body" v-html="htmlContent"></div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount, watch, computed, nextTick } from 'vue'
import Editor from '@tinymce/tinymce-vue'

export default {
    name: 'EditHTMLPage',
    components: {
        Editor
    },
    props: {
        modelValue: {
            type: String,
            default: ''
        },
        editorId: {
            type: String,
            required: true
        },
        inputName: {
            type: String,
            required: true
        },
        inputId: {
            type: String,
            required: true
        },
        placeholder: {
            type: String,
            default: 'Enter content...'
        }
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const htmlContent = ref(props.modelValue || '')
        const showPreview = ref(false)
        let editorInstance = null

        // TinyMCE editor configuration
        const editorConfig = computed(() => ({
            height: 500,
            menubar: true,
            plugins: [
                'advlist', 'anchor', 'autolink', 'charmap', 'code', 'fullscreen',
                'help', 'image', 'insertdatetime', 'link', 'lists', 'media',
                'preview', 'searchreplace', 'table', 'visualblocks', 'wordcount'
            ],
            // Custom preview handler
            preview_styles: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding: 20px; }',
            // Use custom preview if needed, or use built-in preview plugin
            toolbar: 'undo redo | blocks | ' +
                'bold italic underline strikethrough | forecolor backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | ' +
                'removeformat | link image media table | preview | code fullscreen',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            placeholder: props.placeholder,
            branding: false,
            promotion: false,
            // Disable telemetry/analytics to prevent blocked requests
            toolbar_sticky: false,
            // Image upload handler
            images_upload_handler: async (blobInfo, progress) => {
                return new Promise((resolve, reject) => {
                    const formData = new FormData()
                    formData.append('image', blobInfo.blob(), blobInfo.filename())
                    
                    const xhr = new XMLHttpRequest()
                    xhr.withCredentials = false
                    xhr.open('POST', '/admin/session-templates/upload-image')
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    if (csrfToken) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken)
                    }
                    
                    xhr.upload.onprogress = (e) => {
                        progress((e.loaded / e.total) * 100)
                    }
                    
                    xhr.onload = () => {
                        if (xhr.status === 403) {
                            reject({ message: 'HTTP Error: ' + xhr.status, remove: true })
                            return
                        }
                        
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status)
                            return
                        }
                        
                        const json = JSON.parse(xhr.responseText)
                        // TinyMCE expects 'location' in the response
                        // Try both 'location' and 'url' for compatibility
                        const imageUrl = json.location || json.url
                        if (!imageUrl || typeof imageUrl !== 'string') {
                            reject('Invalid JSON: ' + xhr.responseText)
                            return
                        }
                        
                        resolve(imageUrl)
                    }
                    
                    xhr.onerror = () => {
                        reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status)
                    }
                    
                    xhr.send(formData)
                })
            },
            // Table styles and configuration
            table_resize_bars: true,
            table_default_styles: {
                borderCollapse: 'collapse',
                border: '1px solid #000'
            },
            table_default_attributes: {
                border: '1'
            },
            table_class_list: [
                {title: 'None', value: ''},
                {title: 'Table', value: 'table'}
            ],
            // Ensure tables are visible
            content_css: false, // Use default content CSS
            // Table cell default styles
            table_cell_default_styles: {
                border: '1px solid #000',
                padding: '8px 12px'
            },
            // Setup callback to store editor instance
            setup: (editor) => {
                editorInstance = editor
                // Set initial content after editor is ready
                if (props.modelValue) {
                    editor.on('init', () => {
                        editor.setContent(props.modelValue || '')
                        htmlContent.value = props.modelValue || ''
                    })
                }
                
                // Custom preview button handler
                editor.ui.registry.addButton('custompreview', {
                    text: 'Preview',
                    tooltip: 'Preview',
                    onAction: () => {
                        showPreview.value = true
                    }
                })
            },
            // Custom toolbar button for preview (optional - uses built-in preview plugin)
            custom_ui_selector: '.tox-toolbar',
        }))

        function updateHiddenInput() {
            const input = document.getElementById(props.inputId)
            if (input) {
                input.value = htmlContent.value
            }
        }

        function onContentUpdate(content) {
            htmlContent.value = content || ''
            emit('update:modelValue', content || '')
            updateHiddenInput()
        }

        onMounted(async () => {
            // Wait for next tick to ensure DOM is ready
            await nextTick()
            
            // Additional wait for modals - ensure the element is visible before initializing TinyMCE
            // This is especially important for Livewire modals that render dynamically
            const maxAttempts = 10
            let attempts = 0
            
            const initEditor = async () => {
                attempts++
                
                // Check if the editor container is in the DOM and visible
                const editorContainer = document.getElementById(props.editorId)?.closest('.tinymce-wrapper')
                if (!editorContainer) {
                    if (attempts < maxAttempts) {
                        await new Promise(resolve => setTimeout(resolve, 100))
                        return initEditor()
                    } else {
                        console.warn('TinyMCE editor container not found after multiple attempts')
                        return
                    }
                }
                
                // Check if element is visible (important for modals)
                const isVisible = editorContainer.offsetParent !== null || 
                                 window.getComputedStyle(editorContainer).display !== 'none'
                
                if (!isVisible && attempts < maxAttempts) {
                    // Wait a bit more if element exists but isn't visible yet (modal opening)
                    await new Promise(resolve => setTimeout(resolve, 150))
                    return initEditor()
                }
                
                // Set initial content
                if (props.modelValue) {
                    htmlContent.value = props.modelValue
                    updateHiddenInput()
                }

                // Ensure hidden input is updated before form submission
                const form = document.getElementById(props.inputId)?.closest('form')
                if (form) {
                    form.addEventListener('submit', () => {
                        updateHiddenInput()
                    }, true)
                }
            }
            
            await initEditor()
        })

        onBeforeUnmount(() => {
            // Clean up TinyMCE instance when component is destroyed
            if (editorInstance && typeof editorInstance.remove === 'function') {
                try {
                    editorInstance.remove()
                } catch (e) {
                    console.warn('Error removing TinyMCE editor:', e)
                }
            }
            editorInstance = null
        })

        function closePreview() {
            showPreview.value = false
        }

        watch(() => props.modelValue, async (newValue) => {
            // Wait for next tick to ensure editor is ready
            await nextTick()
            
            if (newValue !== htmlContent.value) {
                htmlContent.value = newValue || ''
                
                // Update TinyMCE content if editor is ready
                if (editorInstance && typeof editorInstance.setContent === 'function') {
                    try {
                        editorInstance.setContent(newValue || '')
                    } catch (e) {
                        console.warn('Error setting TinyMCE content:', e)
                    }
                }
                
                updateHiddenInput()
            }
        }, { immediate: false })

        return {
            htmlContent,
            editorConfig,
            onContentUpdate,
            updateHiddenInput,
            showPreview,
            closePreview
        }
    }
}
</script>

<style scoped>
.tinymce-wrapper {
    width: 100%;
}

/* TinyMCE editor styling */
.tinymce-wrapper :deep(.tox-tinymce) {
    border-radius: 4px;
}

/* Dark mode support for TinyMCE */
.dark .tinymce-wrapper :deep(.tox-tinymce) {
    background-color: #27272a;
}

.dark .tinymce-wrapper :deep(.tox-edit-area__iframe) {
    background-color: #27272a;
    color: #e4e4e7;
}

/* Preview Modal Styles */
.preview-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.75);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 20px;
}

.preview-modal-content {
    background-color: white;
    border-radius: 8px;
    width: 100%;
    max-width: 900px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.dark .preview-modal-content {
    background-color: #27272a;
    color: #e4e4e7;
}

.preview-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
}

.dark .preview-modal-header {
    border-bottom-color: #3f3f46;
}

.preview-modal-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.dark .preview-modal-title {
    color: #e4e4e7;
}

.preview-modal-close {
    background: none;
    border: none;
    font-size: 28px;
    line-height: 1;
    color: #6b7280;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s;
}

.preview-modal-close:hover {
    background-color: #f3f4f6;
    color: #111827;
}

.dark .preview-modal-close {
    color: #9ca3af;
}

.dark .preview-modal-close:hover {
    background-color: #3f3f46;
    color: #e4e4e7;
}

.preview-modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
    color: #111827;
}

.dark .preview-modal-body {
    color: #e4e4e7;
}

/* Preview content styling - matches editor content style */
.preview-modal-body :deep(img) {
    max-width: 100%;
    height: auto;
}

.preview-modal-body :deep(table) {
    border-collapse: collapse;
    width: 100%;
    margin: 1rem 0;
    border: 1px solid #000;
}

.preview-modal-body :deep(table td),
.preview-modal-body :deep(table th) {
    border: 1px solid #000;
    padding: 8px 12px;
}

.preview-modal-body :deep(table th) {
    background-color: #f3f4f6;
    font-weight: bold;
}

.dark .preview-modal-body :deep(table) {
    border-color: #e4e4e7;
}

.dark .preview-modal-body :deep(table td),
.dark .preview-modal-body :deep(table th) {
    border-color: #e4e4e7;
}

.dark .preview-modal-body :deep(table th) {
    background-color: #3f3f46;
}
</style>
