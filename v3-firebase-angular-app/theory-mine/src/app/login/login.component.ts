import { Component, OnInit } from '@angular/core';
import { AngularFireAuth } from '@angular/fire/auth';
import { auth } from 'firebase/app';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  constructor(public afAuth: AngularFireAuth) {
  }

  login() {
    this.afAuth.auth.signInWithPopup(new auth.GoogleAuthProvider())
    .then(res => console.log(res));
  }

  logout() {
    this.afAuth.auth.signOut();
  }

  ngOnInit() {
  }

}
