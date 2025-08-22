import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import {Button} from "primeng/button";

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, Button],
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App {
  protected title = 'client';
}
