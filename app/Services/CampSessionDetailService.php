<?php

namespace App\Services;

use App\Models\CampInstance;
use App\Models\CampSessionDetailTemplate;
use Illuminate\Support\Facades\Log;

class CampSessionDetailService
{
    /**
     * Populate session details for a camp instance.
     * Priority:
     * 1. Try to copy from last active session
     * 2. If not found, copy from master template
     * 3. If no template, leave fields empty
     *
     * @param CampInstance $instance
     * @return bool True if details were populated, false otherwise
     */
    public function populateSessionDetails(CampInstance $instance): bool
    {
        // Only populate if fields are empty/null
        if (!$this->needsPopulation($instance)) {
            Log::debug('Camp session details do not need population', [
                'camp_instance_id' => $instance->id,
                'camp_id' => $instance->camp_id,
            ]);
            return false;
        }

        // Try last active session first
        $lastActiveSession = $this->getLastActiveSession($instance);
        
        if ($lastActiveSession) {
            $this->copyFromSession($instance, $lastActiveSession);
            Log::info('Populated camp session details from last active session', [
                'camp_instance_id' => $instance->id,
                'source_session_id' => $lastActiveSession->id,
            ]);
            return true;
        }

        // Fall back to master template
        $template = CampSessionDetailTemplate::getForCamp($instance->camp_id);
        
        if ($template) {
            $this->copyFromTemplate($instance, $template);
            Log::info('Populated camp session details from master template', [
                'camp_instance_id' => $instance->id,
                'camp_id' => $instance->camp_id,
            ]);
            return true;
        }

        Log::info('No source found for camp session details population', [
            'camp_instance_id' => $instance->id,
            'camp_id' => $instance->camp_id,
        ]);
        
        return false;
    }

    /**
     * Check if the instance needs population.
     *
     * @param CampInstance $instance
     * @return bool
     */
    protected function needsPopulation(CampInstance $instance): bool
    {
        return empty($instance->theme_description) && 
               empty($instance->schedule_data) && 
               empty($instance->additional_info);
    }

    /**
     * Get the last active session for the same camp.
     *
     * @param CampInstance $instance
     * @return CampInstance|null
     */
    protected function getLastActiveSession(CampInstance $instance): ?CampInstance
    {
        $query = CampInstance::where('camp_id', $instance->camp_id)
            ->where('is_active', true)
            ->orderBy('start_date', 'desc');
        
        // Exclude current instance if it exists
        if ($instance->id) {
            $query->where('id', '!=', $instance->id);
        }
        
        return $query->first();
    }

    /**
     * Copy details from another session.
     *
     * @param CampInstance $target
     * @param CampInstance $source
     * @return void
     */
    protected function copyFromSession(CampInstance $target, CampInstance $source): void
    {
        $target->theme_description = $source->theme_description;
        $target->schedule_data = $source->schedule_data;
        
        // Handle additional_info - it's stored as JSON in camp_instances
        if ($source->additional_info) {
            // If it's already an array, keep it; otherwise convert
            $target->additional_info = is_array($source->additional_info) 
                ? $source->additional_info 
                : ['content' => $source->additional_info];
        }
        
        if ($source->theme_photos) {
            $target->theme_photos = $source->theme_photos;
        }
    }

    /**
     * Copy details from master template.
     *
     * @param CampInstance $instance
     * @param CampSessionDetailTemplate $template
     * @return void
     */
    protected function copyFromTemplate(CampInstance $instance, CampSessionDetailTemplate $template): void
    {
        $instance->theme_description = $template->theme_description;
        $instance->schedule_data = $template->schedule_data;
        
        // Handle additional_info - template stores as text, instance stores as JSON
        if ($template->additional_info) {
            // Convert text to JSON format for consistency
            $instance->additional_info = ['content' => $template->additional_info];
        }
        
        if ($template->theme_photos) {
            $instance->theme_photos = $template->theme_photos;
        }
    }

    /**
     * Manually populate from last active session (for admin/manager use).
     *
     * @param CampInstance $instance
     * @return bool True if populated, false if no source found
     */
    public function populateFromLastActiveSession(CampInstance $instance): bool
    {
        $lastActiveSession = $this->getLastActiveSession($instance);
        
        if ($lastActiveSession) {
            $this->copyFromSession($instance, $lastActiveSession);
            return true;
        }
        
        return false;
    }

    /**
     * Manually populate from master template (for admin/manager use).
     *
     * @param CampInstance $instance
     * @return bool True if populated, false if no template found
     */
    public function populateFromMasterTemplate(CampInstance $instance): bool
    {
        $template = CampSessionDetailTemplate::getForCamp($instance->camp_id);
        
        if ($template) {
            $this->copyFromTemplate($instance, $template);
            return true;
        }
        
        return false;
    }
}




