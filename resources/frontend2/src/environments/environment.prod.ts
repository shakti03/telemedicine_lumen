import { NgxLoggerLevel } from 'ngx-logger';

export const environment = {
  production: true,
  app_name: 'TeleMedicine',
  logLevel: NgxLoggerLevel.OFF,
  serverLogLevel: NgxLoggerLevel.ERROR,
  api_url: `${(window as any).baseUrl}/api`
};
