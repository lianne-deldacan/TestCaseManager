<?php

use App\Models\IssueCodeSequence;
use App\Models\User;

if (!function_exists('generateIssueNumber')) {
    function generateIssueNumber($project_id)
    {
        $nextSeq = (int) IssueCodeSequence::where('project_id', $project_id)->max('last_sequence') + 1;

        IssueCodeSequence::updateOrCreate(['project_id' => $project_id], [
            'project_id' => $project_id,
            'last_sequence' => $nextSeq
        ]);

        return sprintf('BELL-%d-%03d', $project_id, $nextSeq);
    }
}

if (!function_exists('get_users_with_role')) {
    function get_users_with_role($role = null)
    {
        $users = User::whereHas('roles', function ($q) use ($role) {
            if (is_array($role))
                $q->whereIn('name', $role);
            else
                $q->where('name', $role);
        })->get();

        if (empty($users))
            dd('No user');

        return $users;
    }
};
