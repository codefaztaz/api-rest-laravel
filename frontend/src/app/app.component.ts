import { Component } from '@angular/core';
import { UserService } from './services/user.service';
import { global } from './services/global';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'frontend';
  public token;
	public identity;
  public url;

  constructor
  (
  	private _userService: UserService,
   
  )
  {

    this.url = global.url;
    this.identity = this._userService.getIdentity();
  }


}
