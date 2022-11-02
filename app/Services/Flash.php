<?php

namespace Pimeo\Services;

class Flash
{
    /**
     * Build and push the message to session
     *
     * @param string  $message
     * @param string  $type
     * @param boolean $dismiss
     * @param string  $icon
     * @return void
     */
    public function push($message, $type, $dismiss = true, $icon = '')
    {
        $messages = session()->get('flash_messages', []);

        $messages[] = [
            'message' => $message,
            'type'    => $type,
            'icon'    => empty($icon) ? $type : $icon,
            'dismiss' => $dismiss,
        ];

        session()->flash('flash_messages', $messages);
    }

    /**
     * Add an info message
     *
     * @param  string  $message
     * @param  boolean $dismiss
     * @return self
     */
    public function info($message, $dismiss = true)
    {
        $this->push($message, 'info', $dismiss);

        return $this;
    }

    /**
     * Add a success message
     *
     * @param  string  $message
     * @param  boolean $dismiss
     * @return self
     */
    public function success($message, $dismiss = true)
    {
        $this->push($message, 'success', $dismiss, 'check');

        return $this;
    }

    /**
     * Add a warning message
     *
     * @param  string  $message
     * @param  boolean $dismiss
     * @return self
     */
    public function warning($message, $dismiss = true)
    {
        $this->push($message, 'warning', $dismiss);

        return $this;
    }

    /**
     * Add an error message
     *
     * @param  string  $message
     * @param  boolean $dismiss
     * @return self
     */
    public function error($message, $dismiss = true)
    {
        $this->push($message, 'error', $dismiss, 'ban');

        return $this;
    }
}
