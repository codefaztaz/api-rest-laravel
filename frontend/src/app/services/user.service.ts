import { Injectable } from '@angular/core';
import {  HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { User } from '../models/user';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  public url: string;
  public params: any;
  public user: User;

  constructor(
    public _http:HttpClient
  ) {
      this.url = global.url;
   }

  terst()
  {
    return "hola mundo desde un servicio";
  }

  register(user):Observable<any>
  {
    let json = JSON.stringify(user);
    console.log(json);
   let params = 'json='+json;
    console.log(params);

    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url+'register', params, {headers:headers});
  }

  signup(user, gettoken = null):Observable<any>
  {
      if(gettoken != null)
      {
        user.gettoken = 'true';
      }

      console.log(user);

      let json = JSON.stringify(user);
      let params = 'json='+json; 
      console.log(params);
      let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
      return this._http.post(this.url+'login/', params, {headers:headers});     
  }
  
  
}