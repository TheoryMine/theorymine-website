import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { canActivate } from '@angular/fire/auth-guard';

import { LoginComponent } from './login/login.component';
import { UserComponent } from './user/user.component';
import { AppComponent } from './app.component';

import { AngularFireAuthGuard, hasCustomClaim, redirectUnauthorizedTo, redirectLoggedInTo } from '@angular/fire/auth-guard';

const adminOnly = hasCustomClaim('admin');
const redirectUnauthorizedToLogin = redirectUnauthorizedTo(['login']);
const redirectLoggedInToItems = redirectLoggedInTo(['user']);
const belongsToAccount = (next) => hasCustomClaim(`account-${next.params.id}`);

const routes: Routes = [
  { path: '',             component: LoginComponent,
    pathMatch: 'full',  ...canActivate(redirectLoggedInToItems) },
  { path: 'login',        component: LoginComponent,
    ...canActivate(redirectLoggedInToItems) },
  { path: 'user',         component: UserComponent,
    ...canActivate(redirectUnauthorizedToLogin) },
  // { path: 'admin',        component: AdminComponent,    ...canActivate(adminOnly) },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
