<?php

declare(strict_types=1);

namespace Modules\User\Livewire;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Exception;
>>>>>>> f548be94 (.)
=======
use Exception;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Logout component for handling user logout functionality.
 */
class Logout extends Component
{
    /**
     * Processing state indicator.
     */
    public bool $processing = false;

    /**
     * Handle user logout process.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function logout(): ?RedirectResponse
=======
    public function logout(): null|RedirectResponse
>>>>>>> f548be94 (.)
=======
    public function logout(): null|RedirectResponse
=======
    public function logout(): ?RedirectResponse
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        $this->processing = true;

        try {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('home');
<<<<<<< HEAD
<<<<<<< HEAD
        } catch (\Exception $e) {
            $this->processing = false;
            session()->flash('error', __('Errore durante il logout. Riprova.'));

=======
        } catch (Exception $e) {
            $this->processing = false;
            session()->flash('error', __('Errore durante il logout. Riprova.'));
>>>>>>> f548be94 (.)
=======
        } catch (Exception $e) {
            $this->processing = false;
            session()->flash('error', __('Errore durante il logout. Riprova.'));
=======
        } catch (\Exception $e) {
            $this->processing = false;
            session()->flash('error', __('Errore durante il logout. Riprova.'));

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return null;
        }
    }

    /**
     * Render the logout component view.
     */
    public function render(): View
    {
        return view('user::livewire.logout');
    }
}
