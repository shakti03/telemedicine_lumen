import { NgxLoggerLevel } from 'ngx-logger';

const baseUrl = (window as any).baseUrl;
export const environment = {
  production: true,
  app_name: 'TeleMedicine',
  logLevel: NgxLoggerLevel.OFF,
  serverLogLevel: NgxLoggerLevel.ERROR,
  api_url: `${baseUrl}/api`
};
