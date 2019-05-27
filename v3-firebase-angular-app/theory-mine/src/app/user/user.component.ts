import { Component, OnInit } from '@angular/core';

import { AngularFirestore } from '@angular/fire/firestore';
import * as rxjs from 'rxjs';

@Component({
  selector: 'app-user',
  templateUrl: './user.component.html',
  styleUrls: ['./user.component.css']
})
export class UserComponent implements OnInit {
  items: rxjs.Observable<{}[]> = rxjs.of([]);

  constructor(db: AngularFirestore) {
    // this.items = db.collection('items').valueChanges();
  }
  
  ngOnInit() {
  }

}
